<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_number',
        'name',
        'required_points',
        'exchange_rate',
        'min_exchange',
        'badge_path',
        'benefits',
        'cashback_percent',
    ];

    protected $casts = [
        'benefits' => 'array',
        'exchange_rate' => 'decimal:4',
        'cashback_percent' => 'decimal:2',
    ];
}
