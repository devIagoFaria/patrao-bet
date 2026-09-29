<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'title',
        'description',
        'prize_pool',
        'banner_path',
        'start_date',
        'end_date',
        'rules',
        'games',
        'is_active',
    ];

    protected $casts = [
        'games' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function participants()
    {
        return $this->hasMany(TournamentParticipant::class)->orderBy('score', 'desc');
    }
}
