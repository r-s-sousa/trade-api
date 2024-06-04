<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChampionshipTeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_championship' => $this->id_championship,
            'id_team' => $this->id_team,
            'team_points' => $this->team_points,
            'team_ranking' => $this->team_ranking,
            'team' => new TeamResource($this->whenLoaded('team')),
            'championship' => new ChampionshipResource($this->whenLoaded('championship')),
        ];
    }
}
