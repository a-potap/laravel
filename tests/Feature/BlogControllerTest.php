<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Blog;
use Database\Factories\BlogFactory;

class BlogControllerTest extends TestCase
{
    public function test_the_blog_index_endpoint_returns_a_successful_response(): void
    {
        Blog::factory()->count(5)->create();

        $response = $this->getJson('/api/blog');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'date',
                             'title',
                             'title_en',
                         ]
                     ],
                     'links' => [
                         'first',
                         'last',
                         'prev',
                         'next',
                     ],
                     'meta' => [
                         'current_page',
                         'from',
                         'last_page',
                         'links',
                         'path',
                         'per_page',
                         'to',
                         'total',
                     ]
                 ]);
    }

    public function test_the_blog_index_endpoint_returns_a_successful_response_when_paginated(): void
    {
        Blog::factory()->count(10)->create();

        $response = $this->getJson('/api/blog?page=2');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'date',
                             'title',
                             'title_en',
                         ]
                     ],
                     'links' => [
                         'first',
                         'last',
                         'prev',
                         'next',
                     ],
                     'meta' => [
                         'current_page',
                         'from',
                         'last_page',
                         'links',
                         'path',
                         'per_page',
                         'to',
                         'total',
                     ]
                 ]);
    }

    public function test_the_blog_show_endpoint_returns_a_successful_response(): void
    {
        $blog = Blog::factory()->create();

        $response = $this->getJson("/api/blog/{$blog->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                             'id',
                             'date',
                             'title',
                             'title_en',
                             'text',
                             'text_en'
                     ]
                 ])
                 ->assertJson(['id' => $blog->id]);
    }

    public function test_the_blog_show_endpoint_returns_a_404_response_when_the_blog_post_does_not_exist(): void
    {
        $response = $this->getJson('/api/blog/999');

        $response->assertStatus(404)
                 ->assertJsonStructure([
                     'message'
                 ]);
    }
}
