<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Championship;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChampionshipControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $user = User::factory()->create();
        $championships = Championship::factory()->count(5)->create(['id_created_by' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/championships');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links', 'meta']);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_store()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['create']);

        $data = [
            'name' => 'New Championship aaaa',
        ];

        $response = $this->postJson('/api/v1/championships', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'id_created_by'
            ]
        ]);

        $this->assertDatabaseHas('championships', [
            'name' => 'New Championship aaaa',
            'id_created_by' => $user->id
        ]);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/championships/{$championship->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_show_not_owner()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $otherUser->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/championships/{$championship->id}");

        $response->assertStatus(403);
        $response->assertJson(['message' => 'You do not have permission to view this championship']);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);

        Sanctum::actingAs($user, ['update']);

        $data = ['name' => 'Updated Championship Name'];

        $response = $this->patchJson("/api/v1/championships/{$championship->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('championships', ['id' => $championship->id, 'name' => 'Updated Championship Name']);
    }

    public function test_update_not_owner()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $otherUser->id]);

        Sanctum::actingAs($user, ['update']);

        $data = ['name' => 'Updated Championship Name'];

        $response = $this->patchJson("/api/v1/championships/{$championship->id}", $data);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'You do not have permission to update this championship']);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);

        Sanctum::actingAs($user, ['delete']);

        $response = $this->deleteJson("/api/v1/championships/{$championship->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('championships', ['id' => $championship->id]);
    }

    public function test_destroy_not_owner()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $otherUser->id]);

        Sanctum::actingAs($user, ['delete']);

        $response = $this->deleteJson("/api/v1/championships/{$championship->id}");

        $response->assertStatus(403);
        $response->assertJson(['message' => 'You do not have permission to delete this championship']);
    }
}
