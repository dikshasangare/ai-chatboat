<?php

namespace App\Ai\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Validator;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class StoreProduct implements Tool
{
    public function description(): Stringable|string
    {
        return 'Add a new product to the store catalog. Use this only when the user explicitly '
            . 'asks to add, create, or list a new product for sale — never as a side effect of a search.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()
                ->description('The product name, e.g. "MacBook Air M3"')
                ->required(),

            'category' => $schema->string()
                ->description('The product category, e.g. "Laptop", "Mobile", "Accessories"')
                ->required(),

            'price' => $schema->number()
                ->description('The product price in rupees, e.g. 114999.00')
                ->required(),

            'stock' => $schema->integer()
                ->description('The initial stock quantity available')
                ->required(),

            'description' => $schema->string()
                ->description('Optional short description of the product'),
        ];
    }

    public function handle(Request $request): Stringable|string
    {
        $data = [
            'name' => trim((string) ($request['name'] ?? '')),
            'category' => trim((string) ($request['category'] ?? '')),
            'price' => $request['price'] ?? null,
            'stock' => $request['stock'] ?? null,
            'description' => $request['description'] ?? null,
        ];

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return 'Could not add product: ' . $validator->errors()->first();
        }

        $existing = Product::query()
            ->where('name', $data['name'])
            ->where('category', $data['category'])
            ->first();

        if ($existing) {
            return "A product named '{$data['name']}' already exists in the '{$data['category']}' category (ID: {$existing->id}). "
                . 'Ask the user if they want to update stock/price instead of creating a duplicate.';
        }

        $product = Product::create($data);

        return "Product added successfully: {$product->name} | Category: {$product->category} "
            . "| ₹{$product->price} | Stock: {$product->stock} | ID: {$product->id}";
    }
}
