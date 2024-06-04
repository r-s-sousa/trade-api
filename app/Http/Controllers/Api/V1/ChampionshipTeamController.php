<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Championship;
use App\Models\ChampionshipTeam;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ChampionshipTeamResource;
use App\Http\Requests\V1\StoreChampionshipTeamRequest;
use App\Http\Requests\V1\UpdateChampionshipTeamRequest;
use App\Http\Requests\V1\BulkStoreChampionshipTeamRequest;

class ChampionshipTeamController extends Controller
{
    private User $user;

    public function __construct()
    {
        /** @var User $user */
        $user = auth()->user();
        $this->user = $user;
    }

    public function index(Championship $championship)
    {
        if (!$this->isOwner($championship)) {
            return response()->json(['message' => 'You do not have permission to view this championship teams'], 403);
        }

        $teamsOfChampionship = ChampionshipTeam::with(['team', 'championship'])
                                    ->where('id_championship', $championship->id)
                                    ->paginate();

        return ChampionshipTeamResource::collection($teamsOfChampionship);
    }

    public function store(StoreChampionshipTeamRequest $request)
    {
        return new ChampionshipTeamResource(ChampionshipTeam::create($request->all()));
    }

    public function bulkStore(BulkStoreChampionshipTeamRequest $request)
    {
        try {
            ChampionshipTeam::insert($request->all());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(ChampionshipTeam $championshipTeam)
    {
        $championshipTeam->load(['team', 'championship']);
        $championship = Championship::findOrFail($championshipTeam->id_championship);

        $isOwner = $this->isOwner($championship);
        if (!$isOwner) {
            return response()->json(['message' => 'You do not have permission to view this championship teams'], 403);
        }

        return new ChampionshipTeamResource($championshipTeam);
    }

    public function update(UpdateChampionshipTeamRequest $request, ChampionshipTeam $championshipTeam)
    {
        $championship = Championship::findOrFail($championshipTeam->id_championship);

        $isOwner = $this->isOwner($championship);
        if (!$isOwner) {
            return response()->json(['message' => 'You do not have permission to update this championship teams'], 403);
        }

        $championshipTeam->update($request->all());
    }

    public function destroy(ChampionshipTeam $championshipTeam)
    {
        $canDelete = $this->user != null && $this->user->tokenCan('delete');
        $championship = Championship::findOrFail($championshipTeam->id_championship);
        $isOwner = $this->isOwner($championship);

        if (!$canDelete || !$isOwner) {
            return response()->json(['message' => 'You do not have permission to delete this championship teams'], 403);
        }

        $championshipTeam->delete();
        return response()->noContent();
    }

    private function isOwner(Championship $championship)
    {
        $isOwner = $this->user->id === $championship->id_created_by;
        return $isOwner;
    }
}
