<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
