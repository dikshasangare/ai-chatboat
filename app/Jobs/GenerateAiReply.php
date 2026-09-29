<?php

namespace App\Jobs;

use App\Ai\Agents\ChatAgent;
use App\Events\AiTyping;
use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateAiReply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $userMessage) {}

    public function handle(): void
    {
        broadcast(new AiTyping(true));

        $response = ChatAgent::make()
            ->forUser('global-chat') // conversation key — swap for a per-room id later
            ->prompt($this->userMessage);

        $reply = Message::create([
            'sender_name' => 'AI Assistant',
            'body' => $response->text(),
            'is_ai' => true,
        ]);

        broadcast(new AiTyping(false));
        broadcast(new MessageSent($reply));
    }
}
