<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetCalendarEvents implements Tool
{
    public function description(): string
    {
        return 'Returns the user calendar events.';
    }

    public function handle(Request $request): string
    {
        return json_encode([
            [
                'title' => 'Team Meeting',
                'time' => '10:00 AM'
            ],
            [
                'title' => 'Doctor Appointment',
                'time' => '4:00 PM'
            ]
        ]);
    }
    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        // return [
        //     'value' => $schema->string()->required(),
        // ];
        return [];
    }
}
