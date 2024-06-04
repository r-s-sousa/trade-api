<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\User;
use App\Models\Championship;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamControllertTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $team = Team::factory()->count(5)->create();
        $response = $this->getJson('/api/v1/teams');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links', 'meta']);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_store()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['create']);

        $data = [
            'name' => 'team name',
        ];

        $response = $this->postJson('/api/v1/teams', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name'
            ]
        ]);

        $this->assertDatabaseHas('teams', [
            'name' => 'team name'
        ]);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/teams/{$team->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $data = ['name' => 'Updated team Name'];

        $response = $this->patchJson("/api/v1/teams/{$team->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('teams', ['id' => $team->id, 'name' => 'Updated team Name']);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();

        Sanctum::actingAs($user, ['delete']);

        $response = $this->deleteJson("/api/v1/teams/{$team->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }
}
