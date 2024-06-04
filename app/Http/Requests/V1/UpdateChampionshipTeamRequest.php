<?php

namespace App\Http\Requests\V1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChampionshipTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();
        return $user != null && $user->tokenCan('update');
    }

    public function rules(): array
    {
        return [
            'team_points' => ['required', 'integer'],
            'team_ranking' => ['required', 'integer']
        ];
    }
}
