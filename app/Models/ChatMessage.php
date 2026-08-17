<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';

    const CREATED_AT = 'date';

    protected $fillable = [
        'chat_room_id',
        'user_id',
        'message'
    ];

    protected $casts = [
        'date' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function readReceipts()
    {
        return $this->hasMany(ChatReadReceipt::class);
    }
}
