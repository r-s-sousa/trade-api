<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_team_one' => $this->id_team_one,
            'id_team_two' => $this->id_team_two,
            'id_championship' => $this->id_championship,
            'stage' => $this->stage,
            'team_one_goals' => $this->team_one_goals,
            'team_two_goals' => $this->team_two_goals,
            'team_one' => new TeamResource($this->whenLoaded('teamOne')),
            'team_two' => new TeamResource($this->whenLoaded('teamTwo')),
            'championship' => new ChampionshipResource($this->whenLoaded('championship'))
        ];
    }
}
