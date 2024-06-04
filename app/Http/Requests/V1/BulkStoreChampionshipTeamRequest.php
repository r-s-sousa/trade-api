<?php

namespace App\Http\Requests\V1;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BulkStoreChampionshipTeamRequest extends FormRequest
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
            '*.id_championship' => 'required|exists:championships,id',
            '*.id_team' => [
                'required',
                Rule::exists('teams', 'id'),
                function ($attribute, $value, $fail) {
                    $duplicates = array_filter(array_count_values(array_column($this->input(), 'id_team')), function ($count) {
                        return $count > 1;
                    });
                    if (!empty($duplicates)) {
                        $fail('Duplicate id_team values found in the bulk request.');
                    }
                },
            ],
            '*.team_points' => 'nullable|integer',
            '*.team_ranking' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'id_team.*.exists' => 'One or more id_team values do not exist in the teams table.',
        ];
    }
}
