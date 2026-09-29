<?php

namespace App\Ai\Agents;

use App\Ai\Tools\GetCalendarEvents;
use App\Ai\Tools\GetLocalTime;
use Illuminate\Support\Facades\File;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Groq)]
class PersonalAssistant implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function instructions(): string
    {
        // return "You are a helpful AI assistant.Answer clearly, accurately, and concisely. If you don't know something, say so instead of making it up.";

        return <<<TEXT
            You are a helpful AI assistant.

            IMPORTANT:

            Whenever a user asks about:

            - their current location
            - where they are
            - their city
            - their state
            - their country
            - nearby places
            - weather at their location

            ALWAYS call the GetLocalTime tool.

            Never guess the user's location.
            Always use the tool.
            TEXT;
    }

    public function tools(): iterable
    {

        return [
            new GetLocalTime(),
            new GetCalendarEvents()
        ];
    }
}
