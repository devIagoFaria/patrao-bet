<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Bet;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds to match the dashboard layout.
     */
    public function run(): void
    {
        // 1. Clean tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Deposit::truncate();
        Withdrawal::truncate();
        Bet::truncate();
        User::truncate();
        Setting::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed default configurations
        Setting::set('site_name', 'patrao.bet');
        Setting::set('min_deposit', '20.00');
        Setting::set('max_deposit', '10000.00');
        Setting::set('min_withdrawal', '50.00');
        Setting::set('max_withdrawal', '5000.00');
        Setting::set('bonus_rollover', '10');

        // 2. Create 109 users
        $totalUsersCount = 109;
        $usersWithBalanceCount = 38; // "38 com saldo"
        $totalBalances = 4008.82; // "R$ 4.008,82"

        $users = [];
        
        // Generate balances that sum to exactly 4008.82 across 38 users
        $balances = [];
        $tempSum = 0;
        for ($i = 0; $i < $usersWithBalanceCount - 1; $i++) {
            // Average around 4008.82 / 38 = 105.49
            $val = mt_rand(1000, 20000) / 100;
            $balances[] = $val;
            $tempSum += $val;
        }
        // Last one takes the remainder
        $balances[] = round($totalBalances - $tempSum, 2);

        // Just in case last one is negative, adjust it
        if ($balances[count($balances) - 1] < 0) {
            $diff = abs($balances[count($balances) - 1]) + 10.00;
            $balances[count($balances) - 1] = 10.00;
            // deduct the diff from other balances
            for ($i = 0; $i < count($balances) - 1; $i++) {
                if ($balances[$i] > $diff) {
                    $balances[$i] = round($balances[$i] - $diff, 2);
                    break;
                }
            }
        }

        // Verify total sum
        $actualBalanceSum = array_sum($balances);

        // Make list of names/emails
        $names = [
            'Adriel Oliveira', 'José Silva', 'Marcos Pinto', 'Adriano Moura', 
            'Bruno Vieira', 'Carlos Gomes', 'Felipe Dias', 'Gabriel Rocha', 
            'João Cardoso', 'Lucas Moreira', 'Mateus Araújo', 'Thiago Costa'
        ];

        for ($i = 1; $i <= $totalUsersCount; $i++) {
            $name = ($i <= count($names)) ? $names[$i-1] : "Jogador $i";
            
            // First 38 users get positive balances, rest get 0.00
            $balance = ($i <= $usersWithBalanceCount) ? $balances[$i-1] : 0.00;

            $users[] = User::create([
                'name' => $name,
                'email' => "jogador$i@gmail.com",
                'password' => Hash::make('password123'),
                'balance' => $balance,
                'created_at' => now()->subDays(mt_rand(1, 30))->subHours(mt_rand(1, 23)),
            ]);
        }

        $userIds = collect($users)->pluck('id')->toArray();

        // 3. Create exactly 1051 deposits that sum to exactly R$ 45.145,00
        $totalDepositsCount = 1051;
        $totalDepositsSum = 45145.00;
        
        $depositAmounts = [];
        $tempDepositsSum = 0;
        for ($i = 0; $i < $totalDepositsCount - 1; $i++) {
            // Average around 45145 / 1051 = 42.95. Let's vary between 10.00 and 80.00
            $val = mt_rand(1000, 8000) / 100;
            $depositAmounts[] = $val;
            $tempDepositsSum += $val;
        }
        $depositAmounts[] = round($totalDepositsSum - $tempDepositsSum, 2);

        // Adjust last one if negative or too high
        if ($depositAmounts[count($depositAmounts) - 1] < 1.00) {
            $diff = abs($depositAmounts[count($depositAmounts) - 1]) + 20.00;
            $depositAmounts[count($depositAmounts) - 1] = 20.00;
            // deduct diff from others
            for ($i = 0; $i < count($depositAmounts) - 1; $i++) {
                if ($depositAmounts[$i] > $diff + 5) {
                    $depositAmounts[$i] = round($depositAmounts[$i] - $diff, 2);
                    break;
                }
            }
        }

        // Bulk insert deposits in chunks to be database-friendly and performant
        $depositsData = [];
        for ($i = 0; $i < $totalDepositsCount; $i++) {
            // Distribute dates over last 10 days to feed the chart nicely
            $daysAgo = mt_rand(0, 9);
            $date = now()->subDays($daysAgo)->subHours(mt_rand(0, 23))->subMinutes(mt_rand(0, 59));
            
            $depositsData[] = [
                'user_id' => $userIds[array_rand($userIds)],
                'amount' => $depositAmounts[$i],
                'status' => 'approved',
                'payment_method' => 'pix',
                'transaction_id' => 'E' . mt_rand(10000000, 99999999) . 'T' . mt_rand(10000, 99999),
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        // Chunk insert
        foreach (array_chunk($depositsData, 200) as $chunk) {
            Deposit::insert($chunk);
        }

        // 4. Create exactly 269 withdrawals that sum to exactly R$ 9.800,00
        $totalWithdrawalsCount = 269;
        $totalWithdrawalsSum = 9800.00;

        $withdrawalAmounts = [];
        $tempWithdrawalsSum = 0;
        for ($i = 0; $i < $totalWithdrawalsCount - 1; $i++) {
            // Average around 9800 / 269 = 36.43. Vary between 10.00 and 70.00
            $val = mt_rand(1000, 7000) / 100;
            $withdrawalAmounts[] = $val;
            $tempWithdrawalsSum += $val;
        }
        $withdrawalAmounts[] = round($totalWithdrawalsSum - $tempWithdrawalsSum, 2);

        // Adjust last one if negative
        if ($withdrawalAmounts[count($withdrawalAmounts) - 1] < 1.00) {
            $diff = abs($withdrawalAmounts[count($withdrawalAmounts) - 1]) + 20.00;
            $withdrawalAmounts[count($withdrawalAmounts) - 1] = 20.00;
            for ($i = 0; $i < count($withdrawalAmounts) - 1; $i++) {
                if ($withdrawalAmounts[$i] > $diff + 5) {
                    $withdrawalAmounts[$i] = round($withdrawalAmounts[$i] - $diff, 2);
                    break;
                }
            }
        }

        $withdrawalsData = [];
        for ($i = 0; $i < $totalWithdrawalsCount; $i++) {
            $daysAgo = mt_rand(0, 9);
            $date = now()->subDays($daysAgo)->subHours(mt_rand(0, 23))->subMinutes(mt_rand(0, 59));

            $withdrawalsData[] = [
                'user_id' => $userIds[array_rand($userIds)],
                'amount' => $withdrawalAmounts[$i],
                'status' => 'approved',
                'pix_key' => mt_rand(100000000, 999999999) . mt_rand(10, 99),
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        foreach (array_chunk($withdrawalsData, 200) as $chunk) {
            Withdrawal::insert($chunk);
        }

        // 5. Create 71 active players having bets ("71 jogaram no período")
        $activePlayersCount = 71;
        shuffle($userIds);
        $activePlayerIds = array_slice($userIds, 0, $activePlayersCount);

        $betsData = [];
        $games = ['aviator', 'mines', 'fortune_tiger'];
        $statuses = ['win', 'lose'];

        // Generate bets for active players
        foreach ($activePlayerIds as $uid) {
            // 2-5 bets per active user
            $betsNum = mt_rand(2, 5);
            for ($k = 0; $k < $betsNum; $k++) {
                $betAmount = mt_rand(10, 200);
                $status = $statuses[array_rand($statuses)];
                $multiplier = ($status === 'win') ? (mt_rand(12, 50) / 10) : null;
                $payout = ($status === 'win') ? ($betAmount * $multiplier) : 0.00;
                
                $daysAgo = mt_rand(0, 9);
                $date = now()->subDays($daysAgo)->subHours(mt_rand(0, 23));

                $betsData[] = [
                    'user_id' => $uid,
                    'game' => $games[array_rand($games)],
                    'bet_amount' => $betAmount,
                    'multiplier' => $multiplier,
                    'payout' => $payout,
                    'status' => $status,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }
        }

        foreach (array_chunk($betsData, 200) as $chunk) {
            Bet::insert($chunk);
        }

        // Add 5 pending withdrawals for the admin review panel
        for ($i = 0; $i < 5; $i++) {
            Withdrawal::create([
                'user_id' => $userIds[array_rand($userIds)],
                'amount' => mt_rand(5000, 30000) / 100, // 50 to 300 R$
                'status' => 'pending',
                'pix_key' => 'pixkey' . mt_rand(1000, 9999) . '@email.com',
                'created_at' => now()->subMinutes(mt_rand(5, 240)),
            ]);
        }

        // Add 5 pending deposits for the admin review panel
        for ($i = 0; $i < 5; $i++) {
            Deposit::create([
                'user_id' => $userIds[array_rand($userIds)],
                'amount' => mt_rand(2000, 15000) / 100, // 20 to 150 R$
                'status' => 'pending',
                'payment_method' => 'pix',
                'transaction_id' => 'E' . mt_rand(10000000, 99999999) . 'T' . mt_rand(10000, 99999),
                'created_at' => now()->subMinutes(mt_rand(5, 240)),
            ]);
        }
    }
}
