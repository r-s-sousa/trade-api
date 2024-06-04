<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function championships()
    {
        return $this->belongsToMany(Championship::class, 'championship_teams', 'id_team', 'id_championship');
    }

    public function gameAsTeamOne()
    {
        return $this->hasMany(Game::class, 'id_team_one');
    }

    public function gameAsTeamTwo()
    {
        return $this->hasMany(Game::class, 'id_team_two');
    }
}
