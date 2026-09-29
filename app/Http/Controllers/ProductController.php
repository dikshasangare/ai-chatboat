<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ProductAgent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Responses\StreamableAgentResponse;

class ProductController extends Controller
{
    public function index()
    {
        return view('product');
    }

    public function stream(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $user = $this->resolveUser();
        $conversationId = $this->resolveConversation($user);
        $stream = $conversationId ? (new ProductAgent)->continue($conversationId, as: $user)->stream($request->message) : (new ProductAgent)->forUser($user)->stream($request->message);

        return $stream->then(function (StreamableAgentResponse $response) {
            if ($response->conversationId) {
                session(['product_agent_conversation_id' => $response->conversationId]);
            }
        });
    }

    protected function resolveUser()
    {
        if (! Auth::check()) {
            $user = User::first();
            Auth::login($user);
        }

        return Auth::user();
    }

    protected function resolveConversation($user): ?string
    {
        return session('product_agent_conversation_id')
            ?? DB::table('agent_conversations')
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->value('id');
    }
}
