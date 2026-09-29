<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ChatAgent;
use App\Ai\Agents\SupportBot;
use App\Events\MessageSent;
use App\Jobs\GenerateAiReply;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Ai\Ai;

class ChatController extends Controller
{

    public function index()
    {
        $messages = Message::orderBy('id')->limit(50)->get();

        return view('chat', ['messages' => $messages]);
    }

    public function store(Request $request)
    {
         $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Message::create([
            'body' => $data['body'],
            'is_ai' => false,
        ]);

        $user = User::firstOrCreate(
            ['email' => 'me@local.test'],
            ['name' => 'Me', 'password' => bcrypt('unused')]
        );

        $response = ChatAgent::make()
            ->forUser($user)
            ->prompt($data['body']);

        $reply = Message::create([
            'body' => (string) $response,
            'is_ai' => true,
        ]);

        return response()->json([
            'reply' => $reply,
        ]);
    
    }

    public function poll(Request $request)
    {
        $afterId = (int) $request->query('after_id', 0);

        $messages = Message::where('id', '>', $afterId)
            ->orderBy('id')
            ->get();

        return response()->json(['messages' => $messages]);
    }
}
