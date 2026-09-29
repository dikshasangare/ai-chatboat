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

        .prose pre {
            background: #0f172a;
            color: #f8fafc;
            padding: 18px;
            border-radius: 16px;
            overflow: auto;
            margin: 18px 0;
        }

        .prose code {
            font-size: 14px;
        }

        .prose table {
            width: 100%;
            border-collapse: collapse;
        }

        .prose table td,
        .prose table th {
            border: 1px solid #e5e7eb;
            padding: 10px;
        }

        .prose blockquote {
            border-left: 4px solid #6366f1;
            padding-left: 16px;
            color: #64748b;
        }

        .stream-cursor {
            display: inline-block;
            width: 8px;
            height: 18px;
            background: #6366f1;
            margin-left: 2px;
            vertical-align: middle;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            50% {
                opacity: 0;
            }
        }
    </style>

    <div class="max-w-5xl mx-auto my-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xl flex flex-col h-[calc(100vh-8rem)] overflow-hidden">
        <!-- Header -->
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
            <div class="flex items-center gap-2">
                <span class="text-xs bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 px-2.5 py-1 rounded-md font-semibold border border-indigo-100 dark:border-indigo-900/30">v2.0</span>
                <button class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-3 py-1.5 rounded-lg font-medium text-xs flex items-center gap-1.5 transition-all duration-150 active:scale-[0.97]" id="clearBtn" title="Clear chat history">
                    <i class="fas fa-trash-alt text-slate-400"></i> Clear
                </button>
            </div>
        </div>

        <!-- Messages -->
        <div id="messages" class="flex-1 overflow-y-auto px-6 py-6 space-y-4 bg-gradient-to-b from-gray-50 via-white to-gray-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950">

            <!-- Welcome message (no DB history in this view) -->
            <div class="msg ai flex items-start gap-3 max-w-[85%] msg-animate">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 text-gray-700 dark:text-gray-300 flex items-center justify-center font-bold text-xs shrink-0 shadow-sm border border-gray-200/50 dark:border-gray-600/30">
                    AI
                </div>
                <div class="flex flex-col gap-1.5">
                    <div class="px-4 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 text-gray-900 dark:text-gray-100 rounded-2xl rounded-tl-none text-sm shadow-sm leading-relaxed prose dark:prose-invert max-w-none">
                        👋 Welcome! I'm your AI assistant. I can help you with Laravel, React, Tailwind CSS, PHP, AI Agents, and Debugging.
                    </div>
                    <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500 px-1">Just now</span>
                </div>
            </div>

        </div>

        <!-- Input -->
        <div class="p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
            <div id="typing" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 h-6 pl-1 flex items-center gap-1.5"></div>

            <div class="input-area w-full max-w-[850px] mx-auto">
                <form id="chat-form" class="w-full">
                    <div class="input-wrapper relative flex items-center bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 shadow-sm transition-all p-1.5 pl-2">

                        <input
                            id="message"
                            type="text"
                            placeholder="Ask me anything... (e.g., Explain Quantum Computing)"
                            autocomplete="off"
                            required
                            maxlength="1000"
                            class="flex-1 min-w-0 bg-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400 border-none outline-none focus:ring-0 py-2 pl-3 pr-20 text-sm">

                        <span id="char-counter" class="absolute right-28 top-1/2 -translate-y-1/2 text-[11px] font-semibold text-gray-400 dark:text-gray-500 pointer-events-none z-10">
                            0/1000
                        </span>

                        <button id="sendBtn" type="button" class="send-btn px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 disabled:from-gray-400 disabled:to-gray-400 text-white font-medium text-sm rounded-full shadow-md flex items-center gap-2 transition-all cursor-pointer disabled:cursor-not-allowed shrink-0 inline-flex">
                            <span>Send</span>
                            <svg class="w-4 h-4 transform rotate-45 stroke-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.6/dist/purify.min.js"></script>

    <script>
        const messagesEl = document.getElementById('messages');
        const typingEl = document.getElementById('typing');
        const form = document.getElementById('chat-form');
        const input = document.getElementById('message');
        const sendBtn = document.getElementById('sendBtn');
        const clearBtn = document.getElementById('clearBtn');
        const charCounter = document.getElementById('char-counter');

        let activeEventSource = null;
        let isStreamingActive = false;
        let currentStreamBubble = null;

        // ---------- helpers ----------

        function getFormattedTime() {
            return new Date().toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }

        function scrollToBottom() {
            messagesEl.scrollTo({
                top: messagesEl.scrollHeight,
                behavior: 'smooth'
            });
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        // Append a finished (non-streaming) human message, styled like file 2
        function appendHumanMessage(body) {
            const div = document.createElement('div');
            const timeStr = getFormattedTime();
            div.className = 'msg human flex items-start justify-end gap-3 max-w-[85%] ml-auto msg-animate';
            div.innerHTML = `
                <div class="flex flex-col items-end gap-1.5 order-1">
                    <div class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-2xl rounded-tr-none text-sm shadow-md shadow-indigo-500/10 leading-relaxed whitespace-pre-wrap">${escapeHtml(body)}</div>
                    <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500 px-1">${timeStr}</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs shrink-0 order-2 border border-indigo-100 dark:border-indigo-900/50 shadow-sm">ME</div>
            `;
            messagesEl.appendChild(div);
            scrollToBottom();
        }

        // Create an empty AI bubble (file 2 styling) that we will stream text into
        function createStreamingAiBubble() {
            const div = document.createElement('div');
            const timeStr = getFormattedTime();
            div.className = 'msg ai flex items-start gap-3 max-w-[85%] msg-animate';
            div.innerHTML = `
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 text-gray-700 dark:text-gray-300 flex items-center justify-center font-bold text-xs shrink-0 shadow-sm border border-gray-200/50 dark:border-gray-600/30">AI</div>
                <div class="flex flex-col gap-1.5">
                    <div class="px-4 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 text-gray-900 dark:text-gray-100 rounded-2xl rounded-tl-none text-sm shadow-sm leading-relaxed prose dark:prose-invert max-w-none" data-raw=""></div>
                    <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500 px-1">${timeStr}</span>
                </div>
            `;
            messagesEl.appendChild(div);
            scrollToBottom();
            return div.querySelector('.prose');
        }

        function appendStreamChunk(bubble, chunk) {
            let currentRaw = bubble.dataset.raw || '';
            currentRaw += chunk;
            bubble.dataset.raw = currentRaw;
            let renderedHtml = DOMPurify.sanitize(marked.parse(currentRaw));
            if (isStreamingActive) {
                renderedHtml += '<span class="stream-cursor"></span>';
            }
            bubble.innerHTML = renderedHtml;
            scrollToBottom();
        }

        function showTyping() {
            typingEl.innerHTML = `
                <span>AI is thinking</span>
                <span class="flex gap-1 items-center ml-1 mt-0.5">
                    <span class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full bounce-dot"></span>
                    <span class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full bounce-dot"></span>
                    <span class="w-1.5 h-1.5 bg-indigo-600 dark:bg-indigo-400 rounded-full bounce-dot"></span>
                </span>
            `;
        }

        function clearTyping() {
            typingEl.textContent = '';
        }

        function finishStreaming() {
            if (activeEventSource) {
                try {
                    activeEventSource.close();
                } catch (e) {}
                activeEventSource = null;
            }
            if (isStreamingActive) {
                isStreamingActive = false;
                if (currentStreamBubble) {
                    let finalHtml = currentStreamBubble.innerHTML.replace('<span class="stream-cursor"></span>', '');
                    currentStreamBubble.innerHTML = finalHtml;
                    if (!currentStreamBubble.dataset.raw || currentStreamBubble.dataset.raw.trim() === '') {
                        currentStreamBubble.innerHTML = '✨ No response received.';
                        currentStreamBubble.dataset.raw = 'No response received.';
                    }
                    currentStreamBubble = null;
                }
            }
            clearTyping();
            sendBtn.disabled = false;
            input.disabled = false;
            input.focus();
        }

        // Parse Vercel / data protocol stream chunks (supports 0:"text" format)
        function parseStreamChunk(data) {
            if (!data || data === '[DONE]') return null;
            const quotedMatch = data.match(/^0:"(.*)"$/);
            if (quotedMatch) {
                try {
                    return JSON.parse(`"${quotedMatch[1]}"`);
                } catch (e) {
                    return quotedMatch[1];
                }
            }
            const plainMatch = data.match(/^0:(.*)$/);
            if (plainMatch) {
                try {
                    const parsed = JSON.parse(plainMatch[1]);
                    if (typeof parsed === 'string') return parsed;
                    return plainMatch[1];
                } catch (e) {
                    return plainMatch[1];
                }
            }
            try {
                const obj = JSON.parse(data);
                if (typeof obj === 'string') return obj;
                if (obj.text) return obj.text;
                if (obj.content) return obj.content;
                if (obj.delta) return obj.delta;
                return null;
            } catch (e) {
                return data.length > 1 ? data : null;
            }
        }

        function startStreaming(userMessage) {
            if (activeEventSource) {
                activeEventSource.close();
                activeEventSource = null;
            }

            isStreamingActive = true;
            showTyping();
            currentStreamBubble = createStreamingAiBubble();

            const url = new URL(`{{ route('product.search') }}`, window.location.origin);
            url.searchParams.set('message', userMessage);

            activeEventSource = new EventSource(url.toString());

            activeEventSource.onmessage = (event) => {
                if (typingEl.innerHTML) clearTyping();
                const chunkText = parseStreamChunk(event.data);
                if (chunkText && typeof chunkText === 'string' && chunkText.length > 0) {
                    if (currentStreamBubble) {
                        appendStreamChunk(currentStreamBubble, chunkText);
                    }
                } else if (event.data === '[DONE]') {
                    finishStreaming();
                }
            };

            activeEventSource.onerror = () => {
                if (currentStreamBubble && (!currentStreamBubble.dataset.raw || currentStreamBubble.dataset.raw.length === 0)) {
                    currentStreamBubble.innerHTML = '⚠️ Connection error. Please try again.';
                    currentStreamBubble.dataset.raw = 'Connection error.';
                }
                finishStreaming();
            };

            activeEventSource.addEventListener('finish', () => finishStreaming());
            activeEventSource.addEventListener('done', () => finishStreaming());

            // safety timeout (60 seconds)
            setTimeout(() => {
                if (isStreamingActive && activeEventSource) {
                    if (currentStreamBubble && (!currentStreamBubble.dataset.raw || currentStreamBubble.dataset.raw.length < 3)) {
                        appendStreamChunk(currentStreamBubble, ' [timeout]');
                    }
                    finishStreaming();
                }
            }, 60000);
        }

        // ---------- events ----------

        function sendMessage() {
            const text = input.value.trim();
            if (!text) return;

            if (isStreamingActive) {
                if (activeEventSource) {
                    activeEventSource.close();
                    activeEventSource = null;
                }
                finishStreaming();
            }

            appendHumanMessage(text);
            input.value = '';
            charCounter.textContent = '0/1000';

            sendBtn.disabled = true;
            input.disabled = true;

            startStreaming(text);
        }

        input.addEventListener('input', () => {
            charCounter.textContent = `${input.value.length}/1000`;
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (!sendBtn.disabled && input.value.trim()) {
                    sendMessage();
                }
            }
        });

        sendBtn.addEventListener('click', () => {
            if (!sendBtn.disabled && input.value.trim()) {
                sendMessage();
            }
        });

        // prevent accidental form submission
        form.addEventListener('submit', (e) => e.preventDefault());

        clearBtn.addEventListener('click', () => {
            if (isStreamingActive) {
                if (activeEventSource) {
                    activeEventSource.close();
                    activeEventSource = null;
                }
                finishStreaming();
            }
            messagesEl.innerHTML = '';
            const div = document.createElement('div');
            div.className = 'msg ai flex items-start gap-3 max-w-[85%] msg-animate';
            div.innerHTML = `
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 text-gray-700 dark:text-gray-300 flex items-center justify-center font-bold text-xs shrink-0 shadow-sm border border-gray-200/50 dark:border-gray-600/30">AI</div>
                <div class="flex flex-col gap-1.5">
                    <div class="px-4 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 text-gray-900 dark:text-gray-100 rounded-2xl rounded-tl-none text-sm shadow-sm leading-relaxed">✨ Conversation cleared. How can I help you today?</div>
                    <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500 px-1">${getFormattedTime()}</span>
                </div>
            `;
            messagesEl.appendChild(div);
            scrollToBottom();
            input.focus();
        });

        window.addEventListener('load', () => {
            input.focus();
            scrollToBottom();
        });
    </script>
</x-app-layout>