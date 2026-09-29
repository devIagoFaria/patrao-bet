<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMissionProgress extends Model
{
    protected $table = 'user_mission_progress';

    protected $fillable = [
        'user_id',
        'mission_id',
        'current_value',
        'is_completed',
        'reward_claimed',
        'reset_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'reward_claimed' => 'boolean',
        'current_value' => 'decimal:2',
        'reset_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }
}
