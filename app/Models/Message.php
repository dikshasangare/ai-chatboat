<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        // 'conversation_id',
        // 'sender',
        // 'content',
        'body',
        'is_ai',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
