<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Championship extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_created_by');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'championship_teams', 'id_championship', 'id_team');
    }
}
