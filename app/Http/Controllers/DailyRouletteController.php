<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailySpin;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyRouletteController extends Controller
{
    /**
    private $prizes = [];

    /**
     * Constructor to load dynamic config from settings database.
     */
    public function __construct()
    {
        $this->prizes = [
            0 => [
                'label' => 'R$ ' . number_format(floatval(Setting::get('spin_reward_0_amount', '1.00')), 2, ',', '.') . ' Bônus',
                'type' => 'bonus',
                'amount' => floatval(Setting::get('spin_reward_0_amount', '1.00')),
                'weight' => intval(Setting::get('spin_reward_0_weight', '40'))
            ],
            1 => [
                'label' => 'R$ ' . number_format(floatval(Setting::get('spin_reward_1_amount', '2.00')), 2, ',', '.') . ' Bônus',
                'type' => 'bonus',
                'amount' => floatval(Setting::get('spin_reward_1_amount', '2.00')),
                'weight' => intval(Setting::get('spin_reward_1_weight', '30'))
            ],
            2 => [
                'label' => 'R$ ' . number_format(floatval(Setting::get('spin_reward_2_amount', '5.00')), 2, ',', '.') . ' Bônus',
                'type' => 'bonus',
                'amount' => floatval(Setting::get('spin_reward_2_amount', '5.00')),
                'weight' => intval(Setting::get('spin_reward_2_weight', '18'))
            ],
            3 => [
                'label' => 'R$ ' . number_format(floatval(Setting::get('spin_reward_3_amount', '10.00')), 2, ',', '.') . ' Bônus',
                'type' => 'bonus',
                'amount' => floatval(Setting::get('spin_reward_3_amount', '10.00')),
                'weight' => intval(Setting::get('spin_reward_3_weight', '9'))
            ],
            4 => [
                'label' => 'R$ ' . number_format(floatval(Setting::get('spin_reward_4_amount', '50.00')), 2, ',', '.') . ' Bônus',
                'type' => 'bonus',
                'amount' => floatval(Setting::get('spin_reward_4_amount', '50.00')),
                'weight' => intval(Setting::get('spin_reward_4_weight', '2'))
            ],
            5 => [
                'label' => 'Não foi dessa vez',
                'type' => 'none',
                'amount' => 0.00,
                'weight' => intval(Setting::get('spin_reward_5_weight', '1'))
            ],
        ];
    }

    /**
     * Check if user can spin today.
     */
    public function status()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Não autenticado.'], 401);
        }

        $userId = Auth::id();
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $alreadySpun = DailySpin::where('user_id', $userId)
            ->whereBetween('spun_at', [$todayStart, $todayEnd])
            ->exists();

        if ($alreadySpun) {
            $secondsRemaining = Carbon::tomorrow()->diffInSeconds(Carbon::now());
            return response()->json([
                'success' => true,
                'can_spin' => false,
                'cooldown_remaining' => $secondsRemaining,
                'message' => 'Você já girou a roleta hoje. Volte amanhã!'
            ]);
        }

        return response()->json([
            'success' => true,
            'can_spin' => true
        ]);
    }

    /**
     * Process daily spin.
     */
    public function spin()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Não autenticado.'], 401);
        }

        $user = Auth::user();
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        // Check again on the server side to prevent exploits
        $alreadySpun = DailySpin::where('user_id', $user->id)
            ->whereBetween('spun_at', [$todayStart, $todayEnd])
            ->exists();

        if ($alreadySpun) {
            return response()->json([
                'success' => false,
                'message' => 'Você já girou a roleta hoje!'
            ], 400);
        }

        // Weighted random selection algorithm
        $totalWeight = 0;
        foreach ($this->prizes as $prize) {
            $totalWeight += $prize['weight'];
        }

        $random = rand(1, $totalWeight);
        $currentWeight = 0;
        $selectedPrizeIndex = 5; // Fallback to "Não foi dessa vez"

        foreach ($this->prizes as $index => $prize) {
            $currentWeight += $prize['weight'];
            if ($random <= $currentWeight) {
                $selectedPrizeIndex = $index;
                break;
            }
        }

        $prize = $this->prizes[$selectedPrizeIndex];

        try {
            DB::transaction(function () use ($user, $prize, $selectedPrizeIndex) {
                // Record spin
                DailySpin::create([
                    'user_id' => $user->id,
                    'reward_type' => $prize['type'],
                    'reward_amount' => $prize['amount'],
                    'spun_at' => Carbon::now(),
                ]);

                // Apply reward
                if ($prize['type'] === 'bonus' && $prize['amount'] > 0) {
                    $user->bonus_balance += $prize['amount'];
                    
                    // Simple 3x rollover for daily spin prizes
                    $rolloverMultiplier = floatval(Setting::get('bonus_rollover', '3'));
                    $user->bonus_rollover_target += $prize['amount'] * $rolloverMultiplier;
                    $user->save();

                    // Notify user
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Ganhou na Roleta Diária!',
                        'message' => 'Parabéns! Você ganhou R$ ' . number_format($prize['amount'], 2, ',', '.') . ' de bônus na roleta diária! Rollover: ' . $rolloverMultiplier . 'x.',
                    ]);
                } elseif ($prize['type'] === 'balance' && $prize['amount'] > 0) {
                    $user->balance += $prize['amount'];
                    $user->save();

                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Ganhou na Roleta Diária!',
                        'message' => 'Parabéns! Você ganhou R$ ' . number_format($prize['amount'], 2, ',', '.') . ' em saldo real na roleta diária!',
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'prize_index' => $selectedPrizeIndex,
                'reward_type' => $prize['type'],
                'reward_amount' => $prize['amount'],
                'label' => $prize['label'],
                'message' => $prize['type'] !== 'none' 
                    ? "Parabéns! Você ganhou {$prize['label']}!" 
                    : "Não foi dessa vez! Volte amanhã para tentar novamente."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar giro: ' . $e->getMessage()
            ], 500);
        }
    }
}
