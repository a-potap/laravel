<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsControllerTest extends TestCase
{
    use RefreshDatabase; 
    public function test_the_news_index_endpoint_returns_a_successful_response(): void
    {
        News::factory()->count(5)->create();

        $response = $this->getJson('/api/news');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'text',
                             'text_en',
                             'date',
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

    public function test_the_news_index_endpoint_returns_a_successful_response_when_paginated(): void
    {
        News::factory()->count(15)->create();

        $response = $this->getJson('/api/news?page=2');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'text',
                             'text_en',
                             'date',
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

    public function test_the_news_index_endpoint_returns_empty_collection_when_no_news_exists(): void
    {
        $response = $this->getJson('/api/news');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'text',
                             'text_en',
                             'date',
                         ]
                     ],
                     'links',
                     'meta',
                 ])
                 ->assertJsonCount(0, 'data');
    }

    public function test_the_news_show_endpoint_returns_a_successful_response(): void
    {
        $news = News::factory()->create();

        $response = $this->getJson("/api/news/{$news->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'text',
                         'text_en',
                         'date',
                     ]
                 ]);
    }

    public function test_the_news_show_endpoint_returns_a_404_response_when_news_does_not_exist(): void
    {
        $response = $this->getJson('/api/news/999');

        $response->assertStatus(404)
                 ->assertJsonStructure([
                     'message'
                 ]);
    }

    public function test_the_news_show_endpoint_returns_a_404_response_when_id_is_invalid(): void
    {
        $response = $this->getJson('/api/news/invalid');

        $response->assertStatus(404);
    }
}