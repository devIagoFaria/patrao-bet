<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VipLevel;

class VipLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'level_number' => 1,
                'name' => 'Bronze 1',
                'required_points' => 0,
                'exchange_rate' => 100.0000,
                'min_exchange' => 100,
                'badge_path' => '/assets/images/badges/bronze_1.svg',
                'cashback_percent' => 1.00,
                'benefits' => ['cashback'],
            ],
            [
                'level_number' => 2,
                'name' => 'Bronze 2',
                'required_points' => 5000,
                'exchange_rate' => 90.0000,
                'min_exchange' => 90,
                'badge_path' => '/assets/images/badges/bronze_2.svg',
                'cashback_percent' => 2.00,
                'benefits' => ['cashback', 'vip_club'],
            ],
            [
                'level_number' => 3,
                'name' => 'Bronze 3',
                'required_points' => 12500,
                'exchange_rate' => 80.0000,
                'min_exchange' => 80,
                'badge_path' => '/assets/images/badges/bronze_3.svg',
                'cashback_percent' => 3.00,
                'benefits' => ['cashback', 'vip_club', 'vip_support'],
            ],
            [
                'level_number' => 4,
                'name' => 'Silver 1',
                'required_points' => 25000,
                'exchange_rate' => 70.0000,
                'min_exchange' => 70,
                'badge_path' => '/assets/images/badges/silver_1.svg',
                'cashback_percent' => 4.00,
                'benefits' => ['cashback', 'vip_club', 'vip_support', 'private_tournaments'],
            ],
            [
                'level_number' => 5,
                'name' => 'Silver 2',
                'required_points' => 50000,
                'exchange_rate' => 60.0000,
                'min_exchange' => 60,
                'badge_path' => '/assets/images/badges/silver_2.svg',
                'cashback_percent' => 5.00,
                'benefits' => ['cashback', 'vip_club', 'vip_support', 'private_tournaments', 'vip_bonus'],
            ],
            [
                'level_number' => 6,
                'name' => 'Silver 3',
                'required_points' => 100000,
                'exchange_rate' => 50.0000,
                'min_exchange' => 50,
                'badge_path' => '/assets/images/badges/silver_3.svg',
                'cashback_percent' => 6.00,
                'benefits' => ['cashback', 'vip_club', 'vip_support', 'private_tournaments', 'vip_bonus', 'sports_limits'],
            ],
            [
                'level_number' => 7,
                'name' => 'Gold 1',
                'required_points' => 200000,
                'exchange_rate' => 40.0000,
                'min_exchange' => 40,
                'badge_path' => '/assets/images/badges/gold_1.svg',
                'cashback_percent' => 7.00,
                'benefits' => ['cashback', 'vip_club', 'vip_support', 'private_tournaments', 'vip_bonus', 'sports_limits', 'exclusive_channels', 'vip_manager'],
            ],
            [
                'level_number' => 8,
                'name' => 'Gold 2',
                'required_points' => 500000,
                'exchange_rate' => 30.0000,
                'min_exchange' => 30,
                'badge_path' => '/assets/images/badges/gold_2.svg',
                'cashback_percent' => 8.00,
                'benefits' => ['cashback', 'vip_club', 'vip_support', 'private_tournaments', 'vip_bonus', 'sports_limits', 'exclusive_channels', 'vip_manager', 'concierge', 'manager_bonus'],
            ],
            [
                'level_number' => 9,
                'name' => 'Gold 3',
                'required_points' => 1000000,
                'exchange_rate' => 20.0000,
                'min_exchange' => 20,
                'badge_path' => '/assets/images/badges/gold_3.svg',
                'cashback_percent' => 10.00,
                'benefits' => ['cashback', 'vip_club', 'vip_support', 'private_tournaments', 'vip_bonus', 'sports_limits', 'exclusive_channels', 'vip_manager', 'concierge', 'manager_bonus', 'vip_gifts', 'vip_trips', 'personal_assistant'],
            ],
        ];

        foreach ($levels as $level) {
            VipLevel::updateOrCreate(
                ['level_number' => $level['level_number']],
                $level
            );
        }
    }
}
