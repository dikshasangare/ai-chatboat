<?php

namespace App\Console\Commands;

use App\Ai\Agents\ChatAgent;
use App\Ai\Agents\PersonalAssistant;
use Illuminate\Console\Command;
use Laravel\Ai\Ai;

use function Laravel\Prompts\title;

class ChatCommand extends Command
{
    protected $signature = 'chat';

    protected $description = 'Chat with your personal AI assistant';

    public function handle(): int
    {
        $this->info('🤖 AI Chat Started');
        $this->info("Type 'exit' to quit.\n");

        $messages = [];

        while (true) {
            $prompt = $this->ask('You');

            if (!$prompt) {
                continue;
            }

            if (strtolower($prompt) === 'q' || strtolower($prompt) === 'exit') {
                $this->info('Goodbye!');
                return self::SUCCESS;
            }

            $messages[] = [
                'role' => 'user',
                'content' => $prompt,
            ];

            try {
                $response = PersonalAssistant::make()
                    ->prompt($prompt);
                $reply = $response;
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $reply,
                ];

                $this->newLine();
                $this->line("<fg=green>AI:</> {$reply}");
                $this->newLine();
            } catch (\Throwable $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
