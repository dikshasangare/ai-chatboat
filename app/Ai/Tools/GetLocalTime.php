<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetLocalTime implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return "Returns the user's current city, state, country, latitude, longitude and timezone.";
    }

    /**
     * Execute the tool.
     */

    public function handle(Request $request): string
    {
        Log::info('LOCATION TOOL CALLED');
        return json_encode([
            'city' => 'Pune',
            'state' => 'Maharashtra',
            'country' => 'India',
            'latitude' => 18.5204,
            'longitude' => 73.8567,
            'timezone' => 'Asia/Kolkata',
        ], JSON_PRETTY_PRINT);
    }
    /**
     * Get the tool's schema definition.
     */
    // public function schema(JsonSchema $schema): array
    // {
    //     return [
    //         'value' => $schema->string()->required(),
    //     ];
    // }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
