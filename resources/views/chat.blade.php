<x-app-layout>
    <style>
        @keyframes customFadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .msg-animate {
            animation: customFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes bounceDot {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-4px);
            }
        }

        .bounce-dot {
            animation: bounceDot 1.2s infinite ease-in-out;
        }

        .bounce-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .bounce-dot:nth-child(3) {
            animation-delay: 0.4s;
        }
    </style>

    <div class="max-w-5xl mx-auto my-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xl flex flex-col h-[calc(100vh-8rem)] overflow-hidden">

        <div class="px-6 py-4 bg-gray-50/80 dark:bg-gray-800/40 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white shadow-md shadow-indigo-500/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-gray-900"></span>
                </div>
                <div>
                    <h1 class="text-base font-bold text-gray-900 dark:text-gray-100">AI Assistant</h1>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold tracking-wide flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>Active Now
                    </p>
                </div>
            </div>
            <div>
                <span class="text-xs bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 px-2.5 py-1 rounded-md font-semibold border border-indigo-100 dark:border-indigo-900/30">v2.0</span>
            </div>
        </div>

        <div id="messages" class="flex-1 overflow-y-auto px-6 py-6 space-y-4 bg-gradient-to-b from-gray-50 via-white to-gray-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950">
            @foreach ($messages as $message)
            @if($message->is_ai)
            <div class="msg ai flex items-start gap-3 max-w-[85%] msg-animate">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 text-gray-700 dark:text-gray-300 flex items-center justify-center font-bold text-xs shrink-0 shadow-sm border border-gray-200/50 dark:border-gray-600/30">
                    AI
                </div>
                <div class="flex flex-col gap-1.5">
                    <div class="px-4 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 text-gray-900 dark:text-gray-100 rounded-2xl rounded-tl-none text-sm shadow-sm leading-relaxed">
                        {{ $message->body }}
                    </div>
                    <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500 px-1">
                        {{ $message->created_at ? $message->created_at->format('g:i A') : now()->format('g:i A') }}
                    </span>
                </div>
            </div>
            @else
            <div class="msg human flex items-start justify-end gap-3 max-w-[85%] ml-auto msg-animate">
                <div class="flex flex-col items-end gap-1.5 order-1">
                    <div class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-2xl rounded-tr-none text-sm shadow-md shadow-indigo-500/10 leading-relaxed">
                        {{ $message->body }}
                    </div>
                    <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500 px-1">
                        {{ $message->created_at ? $message->created_at->format('g:i A') : now()->format('g:i A') }}
                    </span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs shrink-0 order-2 border border-indigo-100 dark:border-indigo-900/50 shadow-sm">
                    ME
                </div>
            </div>
            @endif
            @endforeach
        </div>

        <div class="p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
            <div id="typing" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 h-6 pl-1 flex items-center gap-1.5"></div>

            <form id="chat-form" class="flex gap-2 items-center">
                <div class="flex-1 relative">
                    <input
                        type="text"
                        id="body"
                        placeholder="Ask me anything... (e.g., Explain Quantum Computing)"
                        autocomplete="off"
                        required
                        maxlength="1000"
                        class="w-full pl-4 pr-20 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 border border-gray-300 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm transition-all">
                    <span id="char-counter" class="absolute right-4 top-1/2 -translate-y-1/2 text-[11px] font-semibold text-gray-400 dark:text-gray-500 pointer-events-none">
                        0/1000
                    </span>
                </div>
                <button
                    type="submit"
                    id="send-btn"
                    class="px-5 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 disabled:from-gray-400 disabled:to-gray-400 text-white font-medium text-sm rounded-xl shadow-md flex items-center gap-2 transition-all cursor-pointer disabled:cursor-not-allowed shrink-0">
                    <span>Send</span>
                    <svg class="w-4 h-4 transform rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script id="chat-data" type="application/json">
        @json(['storeUrl' => route('chat.store')])
    </script>
    <script>
        const cfg = JSON.parse(document.getElementById('chat-data').textContent);

        const messagesEl = document.getElementById('messages');
        const typingEl = document.getElementById('typing');
        const form = document.getElementById('chat-form');
        const input = document.getElementById('body');
        const sendBtn = document.getElementById('send-btn');
        const charCounter = document.getElementById('char-counter');
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        // Force initial scroll target baseline
        messagesEl.scrollTop = messagesEl.scrollHeight;

        // Counter UI listener
        input.addEventListener('input', () => {
            charCounter.textContent = `${input.value.length}/1000`;
        });

        function getFormattedTime() {
            return new Date().toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }

        function appendMessage(body, isAi) {
            const div = document.createElement('div');
            const timeStr = getFormattedTime();

            if (isAi) {
                div.className = 'msg ai flex items-start gap-3 max-w-[85%] msg-animate';
                div.innerHTML = `
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 text-gray-700 dark:text-gray-300 flex items-center justify-center font-bold text-xs shrink-0 shadow-sm border border-gray-200/50 dark:border-gray-600/30">AI</div>
                    <div class="flex flex-col gap-1.5">
                        <div class="px-4 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 text-gray-900 dark:text-gray-100 rounded-2xl rounded-tl-none text-sm shadow-sm leading-relaxed">${body}</div>
                        <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500 px-1">${timeStr}</span>
                    </div>
                `;
            } else {
                div.className = 'msg human flex items-start justify-end gap-3 max-w-[85%] ml-auto msg-animate';
                div.innerHTML = `
                    <div class="flex flex-col items-end gap-1.5 order-1">
                        <div class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-2xl rounded-tr-none text-sm shadow-md shadow-indigo-500/10 leading-relaxed">${body}</div>
                        <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500 px-1">${timeStr}</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs shrink-0 order-2 border border-indigo-100 dark:border-indigo-900/50 shadow-sm">ME</div>
                `;
            }

            messagesEl.appendChild(div);
            messagesEl.scrollTo({
                top: messagesEl.scrollHeight,
                behavior: 'smooth'
            });
        }

        form.addEventListener('submit', async (ev) => {
            ev.preventDefault();
            const body = input.value.trim();
            if (!body) return;

            sendBtn.disabled = true;
            appendMessage(body, false);
            input.value = '';
            charCounter.textContent = '0/1000'; // Reset explicit length display state

            // Render bouncing dot processing notification layout block
            typingEl.innerHTML = `
                <span>AI is thinking</span>
                <span class="flex gap-1 items-center ml-1 mt-0.5">
                    <span class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full bounce-dot"></span>
                    <span class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full bounce-dot"></span>
                    <span class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full bounce-dot"></span>
                </span>
            `;
            messagesEl.scrollTo({
                top: messagesEl.scrollHeight,
                behavior: 'smooth'
            });

            try {
                const res = await fetch(cfg.storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        Accept: 'application/json',
                    },
                    body: JSON.stringify({
                        body
                    }),
                });
                const data = await res.json();
                appendMessage(data.reply.body, true);
            } catch (err) {
                console.error('Send failed:', err);
                appendMessage('(failed to get a reply: ' + err.message + ')', true);
            } finally {
                typingEl.textContent = '';
                sendBtn.disabled = false;
            }
        });
    </script>
</x-app-layout>