<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_team_one',
        'id_team_two',
        'id_championship',
        'stage',
        'team_one_goals',
        'team_two_goals'
    ];

    public function teamOne()
    {
        return $this->belongsTo(Team::class, 'id_team_one');
    }

    public function teamTwo()
    {
        return $this->belongsTo(Team::class, 'id_team_two');
    }

    public function championship()
    {
        return $this->belongsTo(Championship::class, 'id_championship');
    }
}
