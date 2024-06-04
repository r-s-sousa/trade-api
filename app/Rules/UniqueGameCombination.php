<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueGameCombination implements ValidationRule
{
    protected $teamOne;
    protected $teamTwo;
    protected $championship;
    protected $stage;

    public function __construct($teamOne, $teamTwo, $championship, $stage)
    {
        $this->teamOne = $teamOne;
        $this->teamTwo = $teamTwo;
        $this->championship = $championship;
        $this->stage = $stage;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table('games')
            ->where('id_team_one', $this->teamOne)
            ->where('id_team_two', $this->teamTwo)
            ->where('id_championship', $this->championship)
            ->where('stage', $this->stage)
            ->exists();

        if ($exists) {
            $fail('The combination of team one, team two, championship, and stage must be unique.');
        }
    }
}
