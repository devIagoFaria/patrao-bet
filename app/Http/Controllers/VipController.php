<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VipLevel;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VipController extends Controller
{
    /**
     * Get the authenticated user's VIP status.
     */
    public function status(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado.'], 401);
        }

        $currentLevel = $user->currentVipLevel();
        $nextLevel = $user->nextVipLevel();
        $allLevels = VipLevel::orderBy('level_number', 'asc')->get();

        return response()->json([
            'success' => true,
            'vip_points' => intval($user->vip_points ?? 0),
            'vip_coins' => floatval($user->vip_coins ?? 0.0000),
            'current_level' => $currentLevel,
            'next_level' => $nextLevel,
            'all_levels' => $allLevels,
        ]);
    }

    /**
     * Exchange VIP coins for real cash balance.
     */
    public function exchange(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado.'], 401);
        }

        $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        $exchangeCoinsAmount = intval($request->input('amount'));

        try {
            $result = DB::transaction(function () use ($user, $exchangeCoinsAmount) {
                // Lock the user row for safe balance updating
                $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();

                $currentLevel = $lockedUser->currentVipLevel();

                // Validation: does user have enough coins?
                if ($lockedUser->vip_coins < $exchangeCoinsAmount) {
                    return [
                        'success' => false,
                        'message' => 'Saldo de moedas VIP insuficiente para realizar esta troca.',
                        'code' => 400
                    ];
                }

                // Validation: is it above the minimum exchange for the user's level?
                if ($exchangeCoinsAmount < $currentLevel->min_exchange) {
                    return [
                        'success' => false,
                        'message' => "O valor mínimo de moedas para troca neste nível é de {$currentLevel->min_exchange} moedas.",
                        'code' => 400
                    ];
                }

                // Calculate cash value based on exchange_rate
                // e.g. rate is 100, which means 100 coins = R$ 1.00
                $cashValue = floatval($exchangeCoinsAmount) / floatval($currentLevel->exchange_rate);

                // Perform deduction and credit
                $lockedUser->vip_coins -= $exchangeCoinsAmount;
                $lockedUser->balance += $cashValue;
                $lockedUser->save();

                // Create user notification
                Notification::create([
                    'user_id' => $lockedUser->id,
                    'title' => 'Troca VIP Concluída',
                    'message' => 'Você trocou ' . number_format($exchangeCoinsAmount, 0, ',', '.') . ' moedas VIP por R$ ' . number_format($cashValue, 2, ',', '.') . ' em saldo real.',
                    'is_read' => false,
                ]);

                return [
                    'success' => true,
                    'message' => 'Troca realizada com sucesso! R$ ' . number_format($cashValue, 2, ',', '.') . ' foram creditados no seu saldo.',
                    'vip_coins' => $lockedUser->vip_coins,
                    'balance' => $lockedUser->balance
                ];
            });

            if (!$result['success']) {
                return response()->json(['success' => false, 'message' => $result['message']], $result['code']);
            }

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Error in VipController::exchange: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro interno ao processar a troca. Tente novamente mais tarde.'
            ], 500);
        }
    }
}
