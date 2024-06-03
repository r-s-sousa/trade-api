<?php

namespace App\Http\Requests\V1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'min:3', Rule::unique('teams', 'name')->ignore($this->team)],
        ];
    }
}
