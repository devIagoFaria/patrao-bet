<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_deposit',
        'max_uses',
        'uses_count',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_deposit' => 'decimal:2',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Check if coupon is currently valid.
     */
    public function isValidFor($amount): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_uses !== null && $this->uses_count >= $this->max_uses) {
            return false;
        }

        if ($amount < $this->min_deposit) {
            return false;
        }

        return true;
    }
}
