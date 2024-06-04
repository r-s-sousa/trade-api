<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\ChampionshipResource;
use App\Models\User;
use App\Models\Championship;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreChampionshipRequest;
use App\Http\Requests\V1\UpdateChampionshipRequest;

class ChampionshipController extends Controller
{
    private User $user;

    public function __construct()
    {
        /** @var User $user */
        $user = auth()->user();
        $this->user = $user;
    }

    public function index()
    {
        $champions = Championship::where('id_created_by', $this->user->id)
            ->with(['user', 'teams'])
            ->paginate();

        return ChampionshipResource::collection($champions);
    }

    public function store(StoreChampionshipRequest $request)
    {
        $fields = $request->all();
        $fields['id_created_by'] = $this->user->id;
        return new ChampionshipResource(Championship::create($fields));
    }

    public function show(Championship $championship)
    {
        if (!$this->isOwner($championship)) {
            return response()->json(['message' => 'You do not have permission to view this championship'], 403);
        }

        $championship->load(['user', 'teams']);

        return new ChampionshipResource($championship);
    }

    public function update(UpdateChampionshipRequest $request, Championship $championship)
    {
        if (!$this->isOwner($championship)) {
            return response()->json(['message' => 'You do not have permission to update this championship'], 403);
        }

        $championship->update($request->all());
    }

    public function destroy(Championship $championship)
    {
        $canDelete = $this->user != null && $this->user->tokenCan('delete');
        $isOwner = $this->isOwner($championship);

        if (!$canDelete || !$isOwner) {
            return response()->json(['message' => 'You do not have permission to delete this championship'], 403);
        }

        $championship->delete();
        return response()->noContent();
    }

    private function isOwner(Championship $championship)
    {
        $isOwner = $this->user->id === $championship->id_created_by;
        return $isOwner;
    }
}
