<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'password',
        'balance',
        'kyc_status',
        'kyc_session_url',
        'referred_by',
        'vip_points',
        'vip_coins',
        'bonus_balance',
        'bonus_rollover_target',
        'bonus_rollover_current',
        'affiliate_commission_unpaid',
        'affiliate_commission_paid',
        'commission_model',
        'commission_value',
    ];

    /**
     * Get the user who referred this user.
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Get the users referred by this user.
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'vip_coins' => 'decimal:4',
        ];
    }

    /**
     * Get user's bets.
     */
    public function bets()
    {
        return $this->hasMany(Bet::class);
    }

    /**
     * Get user's deposits.
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get user's withdrawals.
     */
    /**
     * Get user's withdrawals.
     */
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * Get user's avatar URL based on ID (0-49).
     */
    public function getAvatarUrlAttribute()
    {
        $avatarId = $this->id ? ($this->id % 50) : 0;
        return "https://d3l7suk1kl9rwh.cloudfront.net/bb07034d-8844-4243-bc02-f5f11d25efc2/{$avatarId}.png";
    }

    /**
     * Get the user's current VIP level.
     */
    public function currentVipLevel()
    {
        $level = \App\Models\VipLevel::where('required_points', '<=', $this->vip_points ?? 0)
            ->orderBy('required_points', 'desc')
            ->orderBy('level_number', 'desc')
            ->first();

        if (!$level) {
            // Fallback to Bronze 1 if database is not seeded
            return new \App\Models\VipLevel([
                'level_number' => 1,
                'name' => 'Bronze 1',
                'required_points' => 0,
                'exchange_rate' => 100.0000,
                'min_exchange' => 100,
                'badge_path' => '/assets/images/badges/bronze_1.svg',
                'cashback_percent' => 1.00,
                'benefits' => ['cashback']
            ]);
        }

        return $level;
    }

    /**
     * Get the user's next VIP level.
     */
    public function nextVipLevel()
    {
        return \App\Models\VipLevel::where('required_points', '>', $this->vip_points ?? 0)
            ->orderBy('required_points', 'asc')
            ->orderBy('level_number', 'asc')
            ->first();
    }
}
