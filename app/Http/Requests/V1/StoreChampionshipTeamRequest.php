<?php

namespace App\Http\Requests\V1;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\ChampionshipTeam;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\V1\Rules\UniqueChampionshipTeam;

class StoreChampionshipTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();
        return $user != null && $user->tokenCan('create');
    }

    public function rules(): array
    {
        return [
            'id_championship' => [
                'required',
                Rule::exists('championships', 'id')
            ],
            'id_team' => [
                'required',
                Rule::exists('teams', 'id'),
                Rule::unique('championship_teams')->where(function ($query) {
                    return $query->where('id_championship', $this->id_championship);
                })
            ],
            'team_points' => ['nullable', 'integer'],
            'team_ranking' => ['nullable', 'integer'],
        ];
    }
}
