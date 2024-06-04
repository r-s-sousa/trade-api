<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\User;
use App\Models\Championship;
use Laravel\Sanctum\Sanctum;
use App\Models\ChampionshipTeam;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);
        $teamOne = Team::factory()->create();
        $teamTwo = Team::factory()->create();

        $game = Game::factory()->create([
            'id_team_one' => $teamOne->id,
            'id_team_two' => $teamTwo->id,
            'id_championship' => $championship->id,
            'stage' => 'quarter',
            'team_one_goals' => 1,
            'team_two_goals' => 1
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/games/{$championship->id}/matches");

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links', 'meta']);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);
        $teamOne = Team::factory()->create();
        $teamTwo = Team::factory()->create();

        Sanctum::actingAs($user, ['create']);

        $data = [
            'id_team_one' => $teamOne->id,
            'id_team_two' => $teamTwo->id,
            'id_championship' => $championship->id,
            'stage' => 'quarter',
            'team_one_goals' => 1,
            'team_two_goals' => 1
        ];

        $response = $this->postJson('/api/v1/games', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'id_team_one',
                'id_team_two',
                'id_championship',
                'stage',
                'team_one_goals',
                'team_two_goals',
            ]
        ]);

        $this->assertDatabaseHas('games', [
            'id_team_one' => $teamOne->id,
            'id_team_two' => $teamTwo->id,
            'id_championship' => $championship->id,
            'stage' => 'quarter',
            'team_one_goals' => 1,
            'team_two_goals' => 1
        ]);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);
        $teamOne = Team::factory()->create();
        $teamTwo = Team::factory()->create();

        $game = Game::factory()->create([
            'id_team_one' => $teamOne->id,
            'id_team_two' => $teamTwo->id,
            'id_championship' => $championship->id,
            'stage' => 'quarter',
            'team_one_goals' => 1,
            'team_two_goals' => 1
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/games/{$game->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_show_not_owner()
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $otherUser->id]);
        $teamOne = Team::factory()->create();
        $teamTwo = Team::factory()->create();

        $game = Game::factory()->create([
            'id_team_one' => $teamOne->id,
            'id_team_two' => $teamTwo->id,
            'id_championship' => $championship->id,
            'stage' => 'quarter',
            'team_one_goals' => 1,
            'team_two_goals' => 1
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/games/{$game->id}");

        $response->assertStatus(403);
        $response->assertJson(['message' => 'You do not have permission to view this game']);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);
        $teamOne = Team::factory()->create();
        $teamTwo = Team::factory()->create();

        $game = Game::factory()->create([
            'id_team_one' => $teamOne->id,
            'id_team_two' => $teamTwo->id,
            'id_championship' => $championship->id,
            'stage' => 'quarter',
            'team_one_goals' => 1,
            'team_two_goals' => 1
        ]);

        Sanctum::actingAs($user, ['update']);

        $data = [
            'team_one_goals' => 2,
            'team_two_goals' => 2
        ];

        $response = $this->patchJson("/api/v1/games/{$game->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('games', [
            'id_team_one' => $teamOne->id,
            'id_team_two' => $teamTwo->id,
            'id_championship' => $championship->id,
            'stage' => 'quarter',
            'team_one_goals' => 2,
            'team_two_goals' => 2
        ]);
    }

    public function test_update_not_owner()
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $otherUser->id]);
        $teamOne = Team::factory()->create();
        $teamTwo = Team::factory()->create();

        $game = Game::factory()->create([
            'id_team_one' => $teamOne->id,
            'id_team_two' => $teamTwo->id,
            'id_championship' => $championship->id,
            'stage' => 'quarter',
            'team_one_goals' => 1,
            'team_two_goals' => 1
        ]);

        Sanctum::actingAs($user, ['update']);

        $data = [
            'team_one_goals' => 2,
            'team_two_goals' => 2
        ];

        $response = $this->patchJson("/api/v1/games/{$game->id}", $data);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'You do not have permission to update this game']);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);
        $teamOne = Team::factory()->create();
        $teamTwo = Team::factory()->create();

        $game = Game::factory()->create([
            'id_team_one' => $teamOne->id,
            'id_team_two' => $teamTwo->id,
            'id_championship' => $championship->id,
            'stage' => 'quarter',
            'team_one_goals' => 1,
            'team_two_goals' => 1
        ]);

        Sanctum::actingAs($user, ['delete']);

        $response = $this->deleteJson("/api/v1/games/{$game->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('games', ['id' => $game->id]);
    }

    public function test_destroy_not_owner()
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $otherUser->id]);
        $teamOne = Team::factory()->create();
        $teamTwo = Team::factory()->create();

        $game = Game::factory()->create([
            'id_team_one' => $teamOne->id,
            'id_team_two' => $teamTwo->id,
            'id_championship' => $championship->id,
            'stage' => 'quarter',
            'team_one_goals' => 1,
            'team_two_goals' => 1
        ]);

        Sanctum::actingAs($user, ['delete']);

        $response = $this->deleteJson("/api/v1/games/{$game->id}");

        $response->assertStatus(403);
        $response->assertJson(['message' => 'You do not have permission to delete this game']);
    }
}
