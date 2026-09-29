<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchProducts;
use App\Ai\Tools\StoreProduct;
use Laravel\Ai\Attributes\Model;
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
#[Model('llama-3.3-70b-versatile')]
class ProductAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;


    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        // return <<<TEXT
        // You are an AI-powered product assistant for a Laravel e-commerce platform.

        // Your responsibility is to help users with product discovery, pricing, stock availability,
        // category filtering, comparisons, and purchase-related questions.

        // Always use the SearchProducts tool whenever accurate or up-to-date product data is required.

        // Guidelines:
        // - Never invent products, prices, stock levels, or availability.
        // - Base responses strictly on tool results and conversation context.
        // - Use previous conversation memory to answer follow-up questions naturally.
        // - When comparing products, explain differences clearly and concisely.
        // - Keep responses professional, concise, and easy to understand.
        // - If no matching products are found, clearly communicate that to the user.

        // Your goal is to provide accurate, helpful, and reliable shopping assistance.
        // TEXT;
        return <<<TEXT
            You are an AI-powered product assistant for a Laravel e-commerce platform.
    
            Your responsibility is to help users with product discovery, pricing, stock availability,
            category filtering, comparisons, purchase-related questions, and adding new products to
            the catalog when asked.
    
            Always use the SearchProducts tool whenever accurate or up-to-date product data is required.
            Use the StoreProduct tool only when the user explicitly asks to add or create a new product,
            and only after you have all required fields (name, category, price, stock). If any required
            field is missing, ask the user for it before calling the tool — never guess or invent values.
    
            Guidelines:
            - Never invent products, prices, stock levels, or availability.
            - Base responses strictly on tool results and conversation context.
            - Use previous conversation memory to answer follow-up questions naturally.
            - When comparing products, explain differences clearly and concisely.
            - Keep responses professional, concise, and easy to understand.
            - If no matching products are found, clearly communicate that to the user.
    
            Your goal is to provide accurate, helpful, and reliable shopping assistance.
        TEXT;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new SearchProducts(),
            new StoreProduct(),
        ];
    }
}
