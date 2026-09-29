<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentParticipant extends Model
{
    protected $fillable = [
        'tournament_id',
        'user_id',
        'score',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
