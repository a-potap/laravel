<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatReadReceipt extends Model
{
    use HasFactory;

    protected $table = 'chat_read_receipts';

    public $timestamps = false;

    protected $fillable = [
        'chat_message_id',
        'user_id',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function message()
    {
        return $this->belongsTo(ChatMessage::class, 'chat_message_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
