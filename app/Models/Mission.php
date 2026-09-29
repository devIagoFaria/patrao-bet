<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'game_slug',
        'target_value',
        'reward_amount',
        'period',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'target_value' => 'decimal:2',
        'reward_amount' => 'decimal:2',
    ];

    public function userProgress()
    {
        return $this->hasMany(UserMissionProgress::class);
    }
}
