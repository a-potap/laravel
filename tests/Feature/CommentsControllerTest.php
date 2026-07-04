<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Comment;
use Database\Factories\CommentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $blog;
    protected $commentData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blog = Blog::factory()->create();
        $this->commentData = [
            'iduser' => 'TestUser',
            'text' => 'This is a test comment',
            'blog_id' => $this->blog->id,
        ];
    }

    // ==================== INDEX TESTS ====================

    public function test_index_returns_successful_response_with_comments(): void
    {
        Comment::factory()->count(5)->create([
            'blog_id' => $this->blog->id,
        ]);

        $response = $this->getJson("/api/blog/{$this->blog->id}/comments");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'date',
                        'user',
                        'text',
                    ]
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ]
            ]);
    }

    public function test_index_returns_empty_array_when_no_comments(): void
    {
        $response = $this->getJson("/api/blog/{$this->blog->id}/comments");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
            ])
            ->assertJsonStructure([
                'data',
                'meta',
            ]);
    }

    public function test_index_returns_comments_ordered_by_date(): void
    {
        $comment1 = Comment::factory()->create([
            'blog_id' => $this->blog->id,
            'date' => '2024-01-03 10:00:00',
        ]);
        $comment2 = Comment::factory()->create([
            'blog_id' => $this->blog->id,
            'date' => '2024-01-01 10:00:00',
        ]);
        $comment3 = Comment::factory()->create([
            'blog_id' => $this->blog->id,
            'date' => '2024-01-02 10:00:00',
        ]);

        $response = $this->getJson("/api/blog/{$this->blog->id}/comments");

        $response->assertStatus(200);
        $responseData = $response->json('data');

        $this->assertEquals($comment2->id, $responseData[0]['id']);
        $this->assertEquals($comment3->id, $responseData[1]['id']);
        $this->assertEquals($comment1->id, $responseData[2]['id']);
    }

    public function test_index_respects_pagination(): void
    {
        Comment::factory()->count(15)->create([
            'blog_id' => $this->blog->id,
        ]);

        $response = $this->getJson("/api/blog/{$this->blog->id}/comments");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'links',
                    'to',
                    'total',
                ]
            ]);
    }

    public function test_index_with_page_parameter(): void
    {
        Comment::factory()->count(25)->create([
            'blog_id' => $this->blog->id,
        ]);

        $response = $this->getJson("/api/blog/{$this->blog->id}/comments?page=2");

        $response->assertStatus(200)
            ->assertJsonStructure([
              'data',
                'links',
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'links',
                    'to',
                    'total',
                ]
            ])
            ->assertJsonPath('meta.current_page', 2);
    }

    public function test_index_returns_404_for_nonexistent_blog(): void
    {
        $response = $this->getJson('/api/blog/99999/comments');

        $response->assertStatus(200);
    }

    // ==================== STORE TESTS ====================

    public function test_store_returns_created_response_with_comment(): void
    {
        $commentData = [
            'iduser' => 'NewUser',
            'text' => 'New comment text',
            'blog_id' => $this->blog->id,
        ];

        $response = $this->postJson("/api/blog/{$this->blog->id}/comments", $commentData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'date',
                    'user',
                    'text',
                ]
            ])
            ->assertJson([
                'data' => [
                    'user' => 'NewUser',
                    'text' => 'New comment text',
                ]
            ]);

        $this->assertDatabaseCount('blog_coments', 1);
        $this->assertDatabaseHas('blog_coments', [
            'iduser' => 'NewUser',
            'text' => 'New comment text',
            'blog_id' => $this->blog->id,
        ]);
    }

    public function test_store_requires_iduser(): void
    {
        $response = $this->postJson("/api/blog/{$this->blog->id}/comments", [
            'text' => 'Test comment',
            'blog_id' => $this->blog->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'iduser',
                ]
            ]);
    }

    public function test_store_requires_text(): void
    {
        $response = $this->postJson("/api/blog/{$this->blog->id}/comments", [
            'iduser' => 'TestUser',
            'blog_id' => $this->blog->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'text',
                ]
            ]);
    }

    public function test_store_throttles_requests(): void
    {
        $this->withoutExceptionHandling();

        $data = [
            'iduser' => 'TestUser',
            'text' => 'Test comment',
            'blog_id' => $this->blog->id,
        ];

        for ($i = 0; $i < 11; $i++) {
            $this->postJson("/api/blog/{$this->blog->id}/comments", $data);
        }

        $response = $this->postJson("/api/blog/{$this->blog->id}/comments", $data);

        $response->assertStatus(429);
    }

    public function test_store_with_empty_body(): void
    {
        $response = $this->postJson("/api/blog/{$this->blog->id}/comments", []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'iduser',
                    'text',
                ]
            ]);
    }
}