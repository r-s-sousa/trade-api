<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Team;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TeamResource;
use App\Http\Requests\V1\StoreTeamRequest;
use App\Http\Requests\V1\UpdateTeamRequest;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('championships')->paginate();
        return TeamResource::collection($teams);
    }

    public function store(StoreTeamRequest $request)
    {
        return new TeamResource(Team::create($request->all()));
    }

    public function show(Team $team)
    {
        $team->load('championships');
        return new TeamResource($team);
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        $team->update($request->all());
    }

    public function destroy(Team $team)
    {
        /** @var User $user */
        $user = auth()->user();
        $canDelete = $user != null && $user->tokenCan('delete');

        if (!$canDelete) {
            return response()->json(['message' => 'You do not have permission to delete this team'], 403);
        }

        $team->delete();
        return response()->noContent();
    }
}
