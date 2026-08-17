Objective: Generate high-quality HTTP Feature tests for API endpoints.

Core Requirements:
- Location: Always save tests in the tests/Feature directory.
- Base Class: All test classes must extend Tests\TestCase.
- Namespace: Use namespace Tests\Feature;.
- Testing Tool: Use Laravel’s built-in HTTP testing methods (e.g., $this->getJson(), $this->postJson(), $this->putJson()) to interact with the application.Response Handling: Always use Laravel's Fluent Assertions (e.g., assertStatus(), assertJson(), assertJsonStructure()) to validate responses.
- Write negative test cases also.


Code Template:
<?php
namespace Tests\Feature;
use Tests\TestCase;
class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->getJson('/api/resource');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => ['id', 'name']]);
    }
}

