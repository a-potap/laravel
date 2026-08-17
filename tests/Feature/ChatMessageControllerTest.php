<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatMessageControllerTest extends TestCase
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

    /**
     * Helper to create chat messages with specific dates
     * Uses DB::table to avoid Laravel's CREATED_AT timestamp override
     */
    private function createMessage($roomId, $userId, $message, $date = null): ChatMessage
    {
        $date = $date ?? now();

        DB::table('chat_messages')->insert([
            'chat_room_id' => $roomId,
            'user_id' => $userId,
            'message' => $message,
            'date' => $date,
            'updated_at' => $date,
        ]);

        return ChatMessage::where('chat_room_id', $roomId)
            ->where('user_id', $userId)
            ->where('message', $message)
            ->first();
    }

    // ==================== INDEX TESTS ====================

    public function test_index_returns_messages_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $this->createMessage($chatRoom->id, $user->id, 'Hello world!');
        $this->createMessage($chatRoom->id, $user->id, 'Second message');

        $response = $this->actingAs($user)->getJson("/api/chat/rooms/{$chatRoom->id}/messages");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'chat_room_id',
                        'user_id',
                        'message',
                        'date',
                        'updated_at',
                        'user' => [
                            'id',
                            'name',
                            'email',
                        ],
                    ]
                ]
            ]);
    }

    public function test_index_returns_empty_array_when_no_messages(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $response = $this->actingAs($user)->getJson("/api/chat/rooms/{$chatRoom->id}/messages");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
            ]);
    }

    public function test_index_returns_messages_ordered_by_date_descending(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $this->createMessage($chatRoom->id, $user->id, 'First message', now()->subMinutes(10));
        $this->createMessage($chatRoom->id, $user->id, 'Second message', now()->subMinutes(5));
        $this->createMessage($chatRoom->id, $user->id, 'Third message', now());

        $response = $this->actingAs($user)->getJson("/api/chat/rooms/{$chatRoom->id}/messages");

        $response->assertStatus(200)
            ->assertJsonPath('data.0.message', 'Third message')
            ->assertJsonPath('data.1.message', 'Second message')
            ->assertJsonPath('data.2.message', 'First message');
    }

    public function test_index_returns_404_for_non_existent_chat_room(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/chat/rooms/99999/messages');

        $response->assertStatus(404);
    }

    public function test_index_returns_403_when_user_is_not_a_participant(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $otherUser->id]);

        $this->attachParticipant($chatRoom->id, $otherUser->id);
        $this->createMessage($chatRoom->id, $otherUser->id, 'Private message');

        $response = $this->actingAs($user)->getJson("/api/chat/rooms/{$chatRoom->id}/messages");

        $response->assertStatus(403);
    }

    public function test_index_returns_unauthorized_for_guest_user(): void
    {
        $response = $this->getJson('/api/chat/rooms/1/messages');

        $response->assertStatus(401);
    }

    public function test_index_paginates_messages(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        // Create more than 50 messages to test pagination
        for ($i = 0; $i < 55; $i++) {
            $this->createMessage($chatRoom->id, $user->id, "Message {$i}", now()->subMinutes(55 - $i));
        }

        $response = $this->actingAs($user)->getJson("/api/chat/rooms/{$chatRoom->id}/messages");

        $response->assertStatus(200);
        $this->assertCount(50, $response->json('data'));
    }

    public function test_index_includes_user_relationship(): void
    {
        $user = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);
        $this->createMessage($chatRoom->id, $user->id, 'Test message');

        $response = $this->actingAs($user)->getJson("/api/chat/rooms/{$chatRoom->id}/messages");

        $response->assertStatus(200)
            ->assertJsonPath('data.0.user.name', 'John Doe')
            ->assertJsonPath('data.0.user.email', 'john@example.com');
    }

    // ==================== STORE TESTS ====================

    public function test_store_creates_message_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $data = ['message' => 'Hello, this is a test message!'];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/messages", $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'chat_room_id',
                    'user_id',
                    'message',
                    'date',
                    'updated_at',
                    'user',
                ]
            ])
            ->assertJson([
                'data' => [
                    'chat_room_id' => $chatRoom->id,
                    'user_id' => $user->id,
                    'message' => 'Hello, this is a test message!',
                ]
            ]);

        $this->assertDatabaseHas('chat_messages', [
            'chat_room_id' => $chatRoom->id,
            'user_id' => $user->id,
            'message' => 'Hello, this is a test message!',
        ]);
    }

    public function test_store_fails_with_missing_message(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/messages", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }

    public function test_store_fails_with_empty_message(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $data = ['message' => ''];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/messages", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }

    public function test_store_fails_with_message_exceeding_max_length(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $data = ['message' => str_repeat('a', 5001)];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/messages", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }

    public function test_store_fails_with_invalid_message_type(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $data = ['message' => 12345];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/messages", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }

    public function test_store_returns_404_for_non_existent_chat_room(): void
    {
        $user = User::factory()->create();

        $data = ['message' => 'Hello!'];

        $response = $this->actingAs($user)->postJson('/api/chat/rooms/99999/messages', $data);

        $response->assertStatus(404);
    }

    public function test_store_returns_403_when_user_is_not_a_participant(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $otherUser->id]);

        $this->attachParticipant($chatRoom->id, $otherUser->id);

        $data = ['message' => 'Intruding message'];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/messages", $data);

        $response->assertStatus(403);
    }

    public function test_store_returns_unauthorized_for_guest_user(): void
    {
        $data = ['message' => 'Guest message'];

        $response = $this->postJson('/api/chat/rooms/1/messages', $data);

        $response->assertStatus(401);
    }

    public function test_store_sets_correct_user_id(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);
        $this->attachParticipant($chatRoom->id, $otherUser->id);

        $data = ['message' => 'Message from authenticated user'];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/messages", $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'user_id' => $user->id,
                ]
            ]);
    }

    public function test_store_includes_user_relationship_in_response(): void
    {
        $user = User::factory()->create(['name' => 'Jane Smith']);
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $data = ['message' => 'Test message with user data'];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/messages", $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.user.name', 'Jane Smith');
    }

    public function test_store_works_for_group_chat(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create([
            'type' => 'group',
            'created_by' => $user->id,
        ]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $data = ['message' => 'Hello group!'];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/messages", $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'message' => 'Hello group!',
                ]
            ]);
    }

    public function test_store_works_for_private_chat(): void
    {
        $user = User::factory()->create();
        $recipient = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create([
            'type' => 'private',
            'created_by' => $user->id,
        ]);

        $this->attachParticipant($chatRoom->id, $user->id);
        $this->attachParticipant($chatRoom->id, $recipient->id);

        $data = ['message' => 'Private message'];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/messages", $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'message' => 'Private message',
                ]
            ]);
    }

    public function test_store_with_valid_message_at_max_length(): void
    {
        $user = User::factory()->create();
        $chatRoom = ChatRoom::factory()->create(['created_by' => $user->id]);

        $this->attachParticipant($chatRoom->id, $user->id);

        $data = ['message' => str_repeat('a', 5000)];

        $response = $this->actingAs($user)->postJson("/api/chat/rooms/{$chatRoom->id}/messages", $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'message' => str_repeat('a', 5000),
                ]
            ]);
    }
}