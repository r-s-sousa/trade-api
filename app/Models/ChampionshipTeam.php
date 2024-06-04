<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChampionshipTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_championship',
        'id_team',
        'team_points',
        'team_ranking',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'id_team');
    }

    public function championship()
    {
        return $this->belongsTo(Championship::class, 'id_championship');
    }
}
