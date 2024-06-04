<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Game;
use App\Models\User;
use App\Models\Championship;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreGameRequest;
use App\Http\Requests\V1\UpdateGameRequest;
use App\Http\Resources\V1\GameResource;

class GameController extends Controller
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
            return response()->json(['message' => 'You do not have permission to view this game'], 403);
        }

        $gamesOfChampionship = Game::with(['teamOne', 'teamTwo', 'championship'])
                                    ->where('id_championship', $championship->id)
                                    ->paginate();

        return GameResource::collection($gamesOfChampionship);
    }

    public function store(StoreGameRequest $request)
    {
        return new GameResource(Game::create($request->all()));
    }

    public function show(Game $game)
    {
        $game->load(['teamOne', 'teamTwo', 'championship']);
        $championship = Championship::findOrFail($game->id_championship);

        $isOwner = $this->isOwner($championship);
        if (!$isOwner) {
            return response()->json(['message' => 'You do not have permission to view this game'], 403);
        }

        return new GameResource($game);
    }

    public function update(UpdateGameRequest $request, Game $game)
    {
        $championship = Championship::findOrFail($game->id_championship);

        $isOwner = $this->isOwner($championship);
        if (!$isOwner) {
            return response()->json(['message' => 'You do not have permission to update this game'], 403);
        }

        $game->update($request->all());
    }

    public function destroy(Game $game)
    {
        $canDelete = $this->user != null && $this->user->tokenCan('delete');
        $championship = Championship::findOrFail($game->id_championship);
        $isOwner = $this->isOwner($championship);

        if (!$canDelete || !$isOwner) {
            return response()->json(['message' => 'You do not have permission to delete this game'], 403);
        }

        $game->delete();
        return response()->noContent();
    }

    private function isOwner(Championship $championship)
    {
        $isOwner = $this->user->id === $championship->id_created_by;
        return $isOwner;
    }
}
