<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="ChatRoom",
 *     description="Represents a chat room",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The unique identifier of the chat room"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="The type of the chat room (e.g., 'public', 'private')"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the chat room"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="The description of the chat room"
 *     ),
 *     @OA\Property(
 *         property="created_by",
 *         type="integer",
 *         description="The ID of the user who created the chat room"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="The date and time the chat room was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="The date and time the chat room was last updated"
 *     )
 * )
 */
class ChatRoom extends Model
{
    use HasFactory;

    protected $table = 'chat_rooms';

    const CREATED_AT = 'date';

    protected $fillable = [
        'type',
        'name',
        'description',
        'created_by'
    ];

    protected $casts = [
        'date' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'chat_room_user')
                    ->withPivot(['joined_at', 'last_read_at'])
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('date', 'desc');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    public static function getOrCreatePrivateChat(User $user1, User $user2)
    {
        $userIds = [$user1->id, $user2->id];
        sort($userIds);

        $existingChat = static::where('type', 'private')
            ->whereHas('participants', function ($query) use ($userIds) {
                $query->whereIn('user_id', $userIds);
            })
            ->withCount('participants')
            ->having('participants_count', '=', 2)
            ->first();

        if ($existingChat) {
            $existingParticipantIds = $existingChat->participants->pluck('id')->sort()->values()->toArray();
            if ($existingParticipantIds === $userIds) {
                return $existingChat;
            }
        }

        $chatRoom = static::create([
            'type' => 'private',
            'created_by' => $user1->id
        ]);

        $chatRoom->participants()->attach([$user1->id, $user2->id], [
            'joined_at' => now()
        ]);

        return $chatRoom;
    }

    public function hasParticipant(User $user)
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }

    public function getUnreadCountForUser(User $user)
    {
        $participant = $this->participants()->where('user_id', $user->id)->first();

        if (!$participant) {
            return 0;
        }

        $lastReadAt = $participant->pivot->last_read_at;

        if (!$lastReadAt) {
            return $this->messages()->count();
        }

        return $this->messages()->where('date', '>', $lastReadAt)->count();
    }

    public function markAsReadForUser(User $user)
    {
        return $this->participants()->updateExistingPivot($user->id, [
            'last_read_at' => now()
        ]);
    }
}
