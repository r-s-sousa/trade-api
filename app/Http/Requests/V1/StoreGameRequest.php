<?php

namespace App\Http\Requests\V1;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Rules\UniqueGameCombination;
use Illuminate\Foundation\Http\FormRequest;

class StoreGameRequest extends FormRequest
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
            'id_team_one' => ['required', Rule::exists('teams', 'id')],
            'id_team_two' => ['required', Rule::exists('teams', 'id')],
            'id_championship' => ['required', Rule::exists('championships', 'id')],

            'stage' => [
                'required',
                'string',
                'max:50',
                new UniqueGameCombination(
                    $this->input('id_team_one'),
                    $this->input('id_team_two'),
                    $this->input('id_championship'),
                    $this->input('stage')
                ),
            ],
            'team_one_goals' => ['required', 'integer', 'min:0'],
            'team_two_goals' => ['required', 'integer', 'min:0'],
        ];
    }
}
