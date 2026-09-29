<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'game',
        'bet_amount',
        'multiplier',
        'payout',
        'status',
        'transaction_id',
        'round_id',
    ];

    /**
     * Get the user that placed the bet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
