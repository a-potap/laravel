<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhotosControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_photos_index_endpoint_returns_a_successful_response(): void
    {
        Photo::factory()->count(5)->create();

        $response = $this->getJson('/api/photos');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'date_create',
                             'name',
                             'description',
                             'name_en',
                             'description_en',
                             'cover',
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

    public function test_the_photos_index_endpoint_returns_a_successful_response_when_paginated(): void
    {
        Photo::factory()->count(15)->create();

        $response = $this->getJson('/api/photos?page=2');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'date_create',
                             'name',
                             'description',
                             'name_en',
                             'description_en',
                             'cover',
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

    public function test_the_photos_index_endpoint_returns_empty_collection_when_no_photos_exists(): void
    {
        $response = $this->getJson('/api/photos');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'date_create',
                             'name',
                             'description',
                             'name_en',
                             'description_en',
                             'cover',
                         ]
                     ],
                     'links',
                     'meta',
                 ])
                 ->assertJsonCount(0, 'data');
    }

    public function test_the_photos_show_endpoint_returns_a_successful_response(): void
    {
        $photo = Photo::factory()->create();

        $response = $this->getJson("/api/photos/{$photo->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'date_create',
                         'name',
                         'description',
                         'name_en',
                         'description_en',
                         'cover',
                     ]
                 ]);
    }

    public function test_the_photos_show_endpoint_returns_a_404_response_when_photo_does_not_exist(): void
    {
        $response = $this->getJson('/api/photos/999');

        $response->assertStatus(404)
                 ->assertJsonStructure([
                     'message'
                 ]);
    }

    public function test_the_photos_show_endpoint_returns_a_404_response_when_id_is_invalid(): void
    {
        $response = $this->getJson('/api/photos/invalid');

        $response->assertStatus(404);
    }
}