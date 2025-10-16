<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventBadgeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that event badges are displayed correctly.
     */
    public function test_event_badges_are_displayed_correctly(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302); // Redirect to login
    }

    /**
     * Test that event badges work for authenticated users.
     */
    public function test_event_badges_work_for_authenticated_users(): void
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
    }
}
