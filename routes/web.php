<?php

use App\Ai\Agents\SupportBot;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Ai\Ai;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::post('/chat', function (Request $request) {
    $request->validate([
        'message' => 'required|string',
        'conversation_id' => 'nullable|string'
    ]);

    // Instantiate or retrieve the agent conversation instance
    $chat = Ai::agent(SupportBot::class)
        ->conversation($request->input('conversation_id'));

    // Send user prompt and capture the generated response object
    $response = $chat->prompt($request->input('message'));

    return response()->json([
        'conversation_id' => $chat->id(), // Send back to the client to retain memory context
        'reply' => $response->text(),
        'tokens_used' => $response->tokens(),
    ]);
});

// Route::get('/messenger', [ChatController::class, 'index'])->name('messenger.index');
// Route::get('/messenger/{conversation}', [ChatController::class, 'show']);
// Route::post('/messenger/new', [ChatController::class, 'store']);
// Route::post('/messenger/{conversation}/send', [ChatController::class, 'sendMessage']);

Route::middleware('auth')->group(function () {

    Route::get('/', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/messages', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/messages/poll', [ChatController::class, 'poll'])->name('chat.poll');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // product search
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/search', [ProductController::class, 'stream'])->name('product.search');
});

require __DIR__ . '/auth.php';
