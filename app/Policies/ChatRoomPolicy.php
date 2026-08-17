<?php

namespace App\Policies;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatRoomPolicy
{
    use HandlesAuthorization;

    public function view(User $user, ChatRoom $chatRoom): bool
    {
        return $chatRoom->hasParticipant($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function sendMessage(User $user, ChatRoom $chatRoom): bool
    {
        return $chatRoom->hasParticipant($user);
    }

    public function addParticipants(User $user, ChatRoom $chatRoom): bool
    {
        return $chatRoom->type === 'group' && $chatRoom->hasParticipant($user);
    }

    public function removeParticipant(User $user, ChatRoom $chatRoom, $targetUserId): bool
    {
        if ($user->id == $targetUserId) {
            return true;
        }

        return $chatRoom->type === 'group' && $chatRoom->created_by === $user->id;
    }
}
