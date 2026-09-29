<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySpin extends Model
{
    protected $fillable = [
        'user_id',
        'reward_type',
        'reward_amount',
        'spun_at',
    ];

    protected $casts = [
        'reward_amount' => 'decimal:2',
        'spun_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
