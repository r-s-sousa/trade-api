<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChampionshipResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'id_created_by' => $this->id_created_by,
            'user' => new UserResource($this->whenLoaded('user')),
            'teams' => TeamResource::collection($this->whenLoaded('teams'))
        ];
    }
}
