<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_message_to_admin_and_admin_can_read_it(): void
    {
        $admin = User::factory()->create([
            'name' => 'Kader Sari',
            'email' => 'kader@example.com',
            'role' => 'kader',
        ]);

        $parent = User::factory()->create([
            'name' => 'Ibu Rina',
            'email' => 'parent@example.com',
            'role' => 'user',
        ]);

        $this->actingAs($parent);

        $response = $this->postJson('/chat/messages', [
            'message' => 'Halo admin, saya ingin bertanya tentang imunisasi.',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'message' => 'Halo admin, saya ingin bertanya tentang imunisasi.',
            ]);

        $this->actingAs($admin);

        $reply = $this->getJson('/chat/messages?user_id=' . $parent->id);

        $reply->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Halo admin, saya ingin bertanya tentang imunisasi.',
            ]);
    }
}
