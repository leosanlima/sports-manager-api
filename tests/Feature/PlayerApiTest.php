<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerApiTest extends TestCase
{
    #use RefreshDatabase;

    public function test_can_list_players()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Player::factory()->count(5)->create();

        $response = $this->getJson('/api/players');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'first_name', 'last_name', 'position', 'team']]]);
    }

    public function test_can_create_a_player()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $team = Team::factory()->create();

        $payload = [
            'first_name' => 'Tom',
            'last_name' => 'Cruise',
            'position' => 'G',
            'team_id' => $team->id,
            'country' => 'USA',
        ];

        $response = $this->postJson('/api/players', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['first_name' => 'Tom']);
    }

    public function test_can_update_a_player()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $player = Player::factory()->create();

        $payload = [
            'first_name' => 'Novo',
            'last_name' => 'Nome',
            'position' => $player->position,
            'country' => $player->country,
            'team_id' => $player->team_id,
        ];

        $response = $this->putJson("/api/players/{$player->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['first_name' => 'Novo']);
    }

    public function test_can_delete_a_player()
    {

        $admin = \App\Models\User::factory()->create();

        $this->actingAs($admin, 'sanctum');

        $admin->assignRole('admin');

        $player = Player::factory()->create();

        $response = $this->deleteJson("/api/players/{$player->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Player deleted successfully']);
    }

    public function test_returns_not_found_if_player_does_not_exist()
    {
        $admin = \App\Models\User::factory()->create();
        $this->actingAs($admin, 'sanctum');
        $admin->assignRole('admin');

        $response = $this->deleteJson('/api/players/999');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Player not found']);
    }
}
