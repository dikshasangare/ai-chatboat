<!-- resources/views/messenger.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AI Messenger</title>
    <script src="https://jsdelivr.net"></script>
</head>

<body class="bg-gray-100 flex h-screen overflow-hidden">

    <!-- Left Sidebar: Conversations List -->
    <div class="w-80 bg-white border-r flex flex-col h-full">
        <div class="p-4 border-b flex justify-between items-center">
            <h1 class="font-bold text-lg">Chats</h1>
            <button onclick="createNewChat()" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 cursor-pointer">+ New</button>
        </div>
        <div id="conversations-list" class="flex-1 overflow-y-auto divide-y divide-gray-100">
            @foreach($conversations as $chat)
            <div onclick="loadConversation({{ $chat->id }})" id="chat-item-{{ $chat->id }}" class="p-4 cursor-pointer hover:bg-gray-50 chat-thread-item">
                <p class="font-medium text-sm text-gray-800 truncate thread-title">{{ $chat->title }}</p>
                <span class="text-xs text-gray-400">{{ $chat->updated_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Right Window: Active Message Box -->
    <div class="flex-1 flex flex-col h-full bg-slate-50">
        <div id="active-chat-header" class="p-4 border-b bg-white font-semibold text-gray-700">
            Select a conversation to begin messaging
        </div>

        <!-- Chat Bubble Viewport -->
        <div id="chat-viewport" class="flex-1 overflow-y-auto p-6 space-y-4 hidden"></div>

        <!-- Input Section -->
        <div id="input-area" class="p-4 bg-white border-t flex hidden">
            <input id="message-input" type="text" placeholder="Type a message..." class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" onkeydown="handleKeyPress(event)">
            <button onclick="postMessage()" class="ml-2 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 cursor-pointer">Send</button>
        </div>
    </div>

    <script>
        let currentChatId = null;

        async function createNewChat() {
            const res = await fetch('/messenger/new', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const chat = await res.json();

            const itemHtml = `<div onclick="loadConversation(${chat.id})" id="chat-item-${chat.id}" class="p-4 cursor-pointer hover:bg-gray-50 chat-thread-item bg-blue-50/50"><p class="font-medium text-sm text-gray-800 truncate thread-title">${chat.title}</p><span class="text-xs text-gray-400">Just now</span></div>`;
            document.getElementById('conversations-list').insertAdjacentHTML('afterbegin', itemHtml);
            loadConversation(chat.id);
        }

        async function loadConversation(id) {
            currentChatId = id;
            document.querySelectorAll('.chat-thread-item').forEach(el => el.classList.remove('bg-blue-50/50'));
            document.getElementById(`chat-item-${id}`).classList.add('bg-blue-50/50');

            const res = await fetch(`/messenger/${id}`);
            const messages = await res.json();

            const viewport = document.getElementById('chat-viewport');
            const header = document.getElementById('active-chat-header');

            header.innerText = document.querySelector(`#chat-item-${id} .thread-title`).innerText;
            viewport.innerHTML = '';

            messages.forEach(msg => appendBubble(msg.sender, msg.content));

            viewport.classList.remove('hidden');
            document.getElementById('input-area').classList.remove('hidden');
            viewport.scrollTop = viewport.scrollHeight;
            document.getElementById('message-input').focus();
        }

        function appendBubble(sender, text) {
            const viewport = document.getElementById('chat-viewport');
            const isUser = sender === 'user';
            const alignment = isUser ? 'justify-end' : 'justify-start';
            const color = isUser ? 'bg-blue-600 text-white' : 'bg-white text-gray-800 border border-gray-200';

            const bubbleHtml = `
                <div class="flex ${alignment}">
                    <div class="max-w-md rounded-2xl px-4 py-2 text-sm shadow-xs ${color}">
                        ${text}
                    </div>
                </div>`;
            viewport.insertAdjacentHTML('beforeend', bubbleHtml);
            viewport.scrollTop = viewport.scrollHeight;
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                postMessage();
            }
        }

        async function postMessage() {
            const input = document.getElementById('message-input');
            const message = input.value.trim();
            if (!message || !currentChatId) return;

            // Append User Bubble
            appendBubble('user', message);
            input.value = '';

            // Disable input while AI streams (WhatsApp / ChatGPT-like experience)
            input.disabled = true;

            // 1. Send request to the backend stream endpoint
            const response = await fetch(`/messenger/${currentChatId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: message
                })
            });

            // 2. Prepare a placeholder container for the bot bubble text
            const viewport = document.getElementById('chat-viewport');
            const bubbleId = 'bot-stream-' + Date.now();
            const botBubbleHtml = `
        <div class="flex justify-start">
            <div id="${bubbleId}" class="max-w-md rounded-2xl px-4 py-2 text-sm shadow-xs bg-white text-gray-800 border border-gray-200">
                <span class="animate-pulse">●●●</span>
            </div>
        </div>`;
            viewport.insertAdjacentHTML('beforeend', botBubbleHtml);
            viewport.scrollTop = viewport.scrollHeight;

            const botBubble = document.getElementById(bubbleId);
            let isFirstChunk = true;

            // 3. Read the text/event-stream chunk-by-chunk
            const reader = response.body.getReader();
            const decoder = new TextDecoder();

            while (true) {
                const {
                    value,
                    done
                } = await reader.read();
                if (done) break;

                const chunkText = decoder.decode(value);
                const lines = chunkText.split('\n');

                for (const line of lines) {
                    if (line.startsWith('data: ')) {
                        try {
                            const parsed = JSON.parse(line.substring(6));

                            if (isFirstChunk) {
                                botBubble.innerText = ''; // Remove the typing placeholder dots
                                isFirstChunk = false;
                            }

                            // Append text token character to bubble layout in real time
                            botBubble.innerText += parsed.text;
                            viewport.scrollTop = viewport.scrollHeight;
                        } catch (e) {
                            // Handle incomplete chunks smoothly
                        }
                    }
                }
            }

            // Re-enable input for the user
            input.disabled = false;
            input.focus();
        }
    </script>
</body>

</html>