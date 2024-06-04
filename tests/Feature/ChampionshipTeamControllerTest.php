<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\User;
use App\Models\Championship;
use Laravel\Sanctum\Sanctum;
use App\Models\ChampionshipTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChampionshipTeamControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);
        $team = Team::factory()->create();

        $championshipsTeam = ChampionshipTeam::factory()->create([
            'id_championship' => $championship->id,
            'id_team' => $team->id
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/championship-teams/{$championship->id}/teams");

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links', 'meta']);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);
        $team = Team::factory()->create();

        Sanctum::actingAs($user, ['create']);

        $data = [
            'id_championship' => $championship->id,
            'id_team' => $team->id,
            'team_points' => 1,
            'team_ranking' => 2,
        ];

        $response = $this->postJson('/api/v1/championship-teams', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'id_championship',
                'id_team',
                'team_points',
                'team_ranking',
            ]
        ]);

        $this->assertDatabaseHas('championship_teams', [
            'id_championship' => $championship->id,
            'id_team' => $team->id,
            'team_points' => 1,
            'team_ranking' => 2
        ]);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);
        $team = Team::factory()->create();

        $championshipsTeam = ChampionshipTeam::factory()->create([
            'id_championship' => $championship->id,
            'id_team' => $team->id
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/championship-teams/{$championshipsTeam->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_show_not_owner()
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $otherUser->id]);
        $team = Team::factory()->create();

        $championshipsTeam = ChampionshipTeam::factory()->create([
            'id_championship' => $championship->id,
            'id_team' => $team->id
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/v1/championship-teams/{$championshipsTeam->id}");

        $response->assertStatus(403);
        $response->assertJson(['message' => 'You do not have permission to view this championship teams']);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);
        $team = Team::factory()->create();

        $championshipsTeam = ChampionshipTeam::factory()->create([
            'id_championship' => $championship->id,
            'id_team' => $team->id,
            'team_points' => 1,
            'team_ranking' => 1
        ]);

        Sanctum::actingAs($user, ['update']);

        $data = [
            'team_points' => 0,
            'team_ranking' => 0
        ];

        $response = $this->patchJson("/api/v1/championship-teams/{$championshipsTeam->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('championship_teams', [
            'id' => $championshipsTeam->id,
            'id_championship' => $championship->id,
            'id_team' => $team->id,
            'team_points' => 0,
            'team_ranking' => 0
        ]);
    }

    public function test_update_not_owner()
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $otherUser->id]);
        $team = Team::factory()->create();
        $championshipsTeam = ChampionshipTeam::factory()->create([
            'id_championship' => $championship->id,
            'id_team' => $team->id,
            'team_points' => 1,
            'team_ranking' => 1
        ]);

        Sanctum::actingAs($user, ['update']);

        $data = [
            'team_points' => 0,
            'team_ranking' => 0
        ];

        $response = $this->patchJson("/api/v1/championship-teams/{$championshipsTeam->id}", $data);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'You do not have permission to update this championship teams']);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $user->id]);
        $team = Team::factory()->create();
        $championshipsTeam = ChampionshipTeam::factory()->create([
            'id_championship' => $championship->id,
            'id_team' => $team->id,
            'team_points' => 1,
            'team_ranking' => 1
        ]);

        Sanctum::actingAs($user, ['delete']);

        $response = $this->deleteJson("/api/v1/championship-teams/{$championshipsTeam->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('championship_teams', ['id' => $championshipsTeam->id]);
    }

    public function test_destroy_not_owner()
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();
        $championship = Championship::factory()->create(['id_created_by' => $otherUser->id]);
        $team = Team::factory()->create();
        $championshipsTeam = ChampionshipTeam::factory()->create([
            'id_championship' => $championship->id,
            'id_team' => $team->id,
            'team_points' => 1,
            'team_ranking' => 1
        ]);

        Sanctum::actingAs($user, ['delete']);

        $response = $this->deleteJson("/api/v1/championship-teams/{$championshipsTeam->id}");

        $response->assertStatus(403);
        $response->assertJson(['message' => 'You do not have permission to delete this championship teams']);
    }
}
