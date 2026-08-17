<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatRoomControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to attach user to chat room without timestamps
     */
    private function attachParticipant($chatRoomId, $userId): void
    {
        DB::table('chat_room_user')->insert([
            'chat_room_id' => $chatRoomId,
            'user_id' => $userId,
            'joined_at' => now(),
        ]);
    }

    public function test_index_returns_chat_rooms_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $response = $this->actingAs($user)->getJson('/api/chat/rooms');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'name',
                        'description',
                        'created_by',
                        'date',
                        'updated_at',
                        'participants',
                        'unread_count',
                    ]
                ]
            ]);
    }

    public function test_index_returns_unauthorized_for_guest_user(): void
    {
        $response = $this->getJson('/api/chat/rooms');

        $response->assertStatus(401);
    }

    public function test_store_creates_group_chat_room(): void
    {
        $user = User::factory()->create();
        $participant = User::factory()->create();

        $data = [
            'name' => 'Project Team',
            'description' => 'Discussion about project tasks',
            'participant_ids' => [$participant->id],
        ];

        $response = $this->actingAs($user)->postJson('/api/chat/rooms', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'name',
                    'description',
                    'created_by',
                    'date',
                    'updated_at',
                    'participants',
                    'unread_count',
                ]
            ])
            ->assertJson([
                'data' => [
                    'type' => 'group',
                    'name' => 'Project Team',
                    'created_by' => $user->id,
                ]
            ]);

        $this->assertDatabaseHas('chat_rooms', [
            'name' => 'Project Team',
            'type' => 'group',
        ]);

        $chatRoom = ChatRoom::where('name', 'Project Team')->first();
        $this->assertTrue($chatRoom->participants()->whereIn('user_id', [$user->id, $participant->id])->count() === 2);
    }

    public function test_store_fails_with_missing_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/chat/rooms', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'participant_ids']);
    }

    public function test_store_fails_with_invalid_participant_ids(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Test Room',
            'participant_ids' => [99999],
        ];

        $response = $this->actingAs($user)->postJson('/api/chat/rooms', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['participant_ids.0']);
    }

    public function test_store_fails_with_empty_participant_ids(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Test Room',
            'participant_ids' => [],
        ];

        $response = $this->actingAs($user)->postJson('/api/chat/rooms', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['participant_ids']);
    }

    public function test_show_returns_chat_room_by_id(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $response = $this->actingAs($user)->getJson("/api/chat/rooms/{$chatRoom->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'name',
                    'description',
                    'created_by',
                    'date',
                    'updated_at',
                    'participants',
                    'unread_count',
                ]
            ])
            ->assertJson(['data' => ['id' => $chatRoom->id]]);
    }

    public function test_show_returns_404_for_non_existent_chat_room(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/chat/rooms/99999');

        $response->assertStatus(404);
    }

    public function test_show_returns_403_when_user_is_not_a_participant(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $otherUser->id]);

        $this->attachParticipant($chatRoom->id, $otherUser->id);

        $response = $this->actingAs($user)->getJson("/api/chat/rooms/{$chatRoom->id}");

        $response->assertStatus(403);
    }

    public function test_show_returns_unauthorized_for_guest_user(): void
    {
        $response = $this->getJson('/api/chat/rooms/1');

        $response->assertStatus(401);
    }

    public function test_get_or_create_private_chat_creates_new_private_chat(): void
    {
        $user = User::factory()->create();
        $recipient = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/chat/rooms/private?recipient_id=' . $recipient->id);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'participants',
                ]
            ])
            ->assertJson(['data' => ['type' => 'private']]);

        $this->assertDatabaseHas('chat_rooms', ['type' => 'private']);
    }

    public function test_get_or_create_private_chat_returns_existing_private_chat(): void
    {
        $user = User::factory()->create();
        $recipient = User::factory()->create();

        // Create private chat directly
        $chatRoom = ChatRoom::create([
            'type' => 'private',
            'created_by' => $user->id,
        ]);

        $this->attachParticipant($chatRoom->id, $user->id);
        $this->attachParticipant($chatRoom->id, $recipient->id);

        $response = $this->actingAs($user)->postJson('/api/chat/rooms/private?recipient_id=' . $recipient->id);

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $chatRoom->id]]);
    }

    public function test_get_or_create_private_chat_fails_with_missing_recipient_id(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/chat/rooms/private');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['recipient_id']);
    }

    public function test_get_or_create_private_chat_fails_with_invalid_recipient_id(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/chat/rooms/private?recipient_id=99999');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['recipient_id']);
    }

    public function test_get_or_create_private_chat_fails_when_recipient_is_self(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/chat/rooms/private?recipient_id=' . $user->id);

        $response->assertStatus(404);
    }

    public function test_get_or_create_private_chat_returns_unauthorized_for_guest_user(): void
    {
        $response = $this->postJson('/api/chat/rooms/private?recipient_id=1');

        $response->assertStatus(401);
    }

    public function test_add_participants_adds_users_to_chat_room(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create([
            'type' => 'group',
            'created_by' => $user->id,
        ]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $newParticipant = User::factory()->create();

        $data = ['user_ids' => [$newParticipant->id]];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/participants", $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'participants',
                ]
            ]);

        $this->assertDatabaseHas('chat_room_user', [
            'chat_room_id' => $chatRoom->id,
            'user_id' => $newParticipant->id,
        ]);
    }

    public function test_add_participants_fails_with_missing_user_ids(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create([
            'type' => 'group',
            'created_by' => $user->id,
        ]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/participants", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_ids']);
    }

    public function test_add_participants_fails_with_invalid_user_ids(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create([
            'type' => 'group',
            'created_by' => $user->id,
        ]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $data = ['user_ids' => [99999]];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/participants", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_ids.0']);
    }

    public function test_add_participants_fails_with_404_for_non_existent_chat_room(): void
    {
        $user = User::factory()->create();
        $newParticipant = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/chat/rooms/99999/participants', [
            'user_ids' => [$newParticipant->id],
        ]);

        $response->assertStatus(404);
    }

    public function test_add_participants_fails_with_403_when_user_is_not_participant(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create([
            'type' => 'group',
            'created_by' => $otherUser->id,
        ]);

        $this->attachParticipant($chatRoom->id, $otherUser->id);

        $newParticipant = User::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/participants", [
            'user_ids' => [$newParticipant->id],
        ]);

        $response->assertStatus(403);
    }

    public function test_remove_participant_removes_user_from_chat_room(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create([
            'type' => 'group',
            'created_by' => $user->id,
        ]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $targetUser = User::factory()->create();
        $this->attachParticipant($chatRoom->id, $targetUser->id);

        $response = $this->actingAs($user)->deleteJson("/api/chat/rooms/{$chatRoom->id}/participants/{$targetUser->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Participant removed successfully']);

        $this->assertDatabaseMissing('chat_room_user', [
            'chat_room_id' => $chatRoom->id,
            'user_id' => $targetUser->id,
        ]);
    }

    public function test_remove_participant_allows_user_to_remove_themselves(): void
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create([
            'type' => 'group',
            'created_by' => $creator->id,
        ]);

        $this->attachParticipant($chatRoom->id, $user->id);
        $this->attachParticipant($chatRoom->id, $creator->id);

        $response = $this->actingAs($user)->deleteJson("/api/chat/rooms/{$chatRoom->id}/participants/{$user->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Participant removed successfully']);
    }

    public function test_remove_participant_fails_with_404_for_non_existent_chat_room(): void
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/chat/rooms/99999/participants/{$targetUser->id}");

        $response->assertStatus(404);
    }

    public function test_remove_participant_fails_with_403_when_user_cannot_remove(): void
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create([
            'type' => 'group',
            'created_by' => $creator->id,
        ]);

        $this->attachParticipant($chatRoom->id, $user->id);
        $this->attachParticipant($chatRoom->id, $creator->id);

        $targetUser = User::factory()->create();
        $this->attachParticipant($chatRoom->id, $targetUser->id);

        // User is not the creator, trying to remove someone else
        $response = $this->actingAs($user)->deleteJson("/api/chat/rooms/{$chatRoom->id}/participants/{$targetUser->id}");

        $response->assertStatus(403);
    }

    public function test_mark_as_read_marks_messages_as_read(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/read");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Messages marked as read']);

        $this->assertDatabaseHas('chat_room_user', [
            'chat_room_id' => $chatRoom->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_mark_as_read_fails_with_404_for_non_existent_chat_room(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/chat/rooms/99999/read');

        $response->assertStatus(404);
    }

    public function test_mark_as_read_fails_with_403_when_user_is_not_a_participant(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $otherUser->id]);

        $this->attachParticipant($chatRoom->id, $otherUser->id);

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/read");

        $response->assertStatus(403);
    }

    public function test_mark_as_read_returns_unauthorized_for_guest_user(): void
    {
        $response = $this->postJson('/api/chat/rooms/1/read');

        $response->assertStatus(401);
    }
}