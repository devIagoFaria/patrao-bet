<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlayFiverController extends Controller
{
    /**
     * Handle PlayFiver Webhook Callback.
     */
    public function handleCallback(Request $request)
    {
        // LOG COMPLETO DA REQUISIÇÃO PARA DEBUG
        Log::info('--- PLAYFIVER WEBHOOK INÍCIO ---');
        Log::info('URL: ' . $request->fullUrl());
        Log::info('IP: ' . $request->ip());
        Log::info('Method: ' . $request->method());
        Log::info('Headers:', $request->headers->all());
        Log::info('Payload:', $request->all());

        $data = $request->all();
        
        // A PlayFiver pode usar 'type' ou 'action' para identificar a chamada
        $type = $data['type'] ?? $data['action'] ?? '';
        $typeLower = strtolower($type);

        // Mapeamento de tipos da PlayFiver (Case Insensitive)
        if (in_array($typeLower, ['balance', 'user_balance', 'getbalance'])) {
            $response = $this->getUserBalance($data);
            Log::info('PlayFiver Balance Response:', $response->getData(true));
            return $response;
        }

        if (in_array($typeLower, ['winbet', 'refund', 'game_callback', 'bet', 'win', 'cancel', 'result'])) {
            $response = $this->processGameCallback($data);
            Log::info('PlayFiver Game Callback Response Final:', $response->getData(true));
            return $response;
        }

        Log::warning('PlayFiver Webhook Tipo Desconhecido:', ['type' => $type]);
        return response()->json([
            'status' => 0,
            'msg' => 'Tipo de requisição inválido'
        ]);
    }

    /**
     * Return user balance to PlayFiver.
     */
    private function getUserBalance($data)
    {
        $userId = $data['user_code'] ?? null;
        $user = User::find($userId);

        if (!$user) {
            Log::error('PlayFiver Webhook Error: Usuário não encontrado', ['user_code' => $userId]);
            return response()->json([
                'status' => 0,
                'msg' => 'Usuário não encontrado'
            ]);
        }

        $finalBalance = (float)number_format($user->balance, 2, '.', '');
        return response()->json([
            'status' => 1,
            'msg' => 'SUCCESS',
            'user_code' => $user->id,
            'user_balance' => $finalBalance,
            'balance' => $finalBalance
        ]);
    }

    /**
     * Process game result (bet/win) from PlayFiver.
     */
    private function processGameCallback($data)
    {
        $userId = $data['user_code'] ?? null;
        $user = User::find($userId);

        if (!$user) {
            Log::error('PlayFiver Webhook Error: Usuário não encontrado', ['user_code' => $userId]);
            return response()->json([
                'status' => 0,
                'msg' => 'Usuário não encontrado'
            ]);
        }

        // A PlayFiver envia os dados da aposta dentro de um objeto 'slot' ou na raiz
        $slotData = $data['slot'] ?? $data;
        $betAmount = (float)($slotData['bet'] ?? $slotData['bet_amount'] ?? 0);
        $winAmount = (float)($slotData['win'] ?? $slotData['win_amount'] ?? 0);
        $gameCode = $slotData['game_code'] ?? 'N/A';
        $type = $data['type'] ?? $data['action'] ?? '';
        
        Log::info("Processando Callback: {$type} | Jogo: {$gameCode} | Bet: {$betAmount} | Win: {$winAmount} | Saldo Atual: {$user->balance}");

        // Se for um Refund, tratamos como um ganho do valor apostado anteriormente
        if ($type === 'Refund') {
            $winAmount = (float)($slotData['win'] ?? 0);
            $betAmount = 0; 
        }

        // Verificação Crítica de Saldo para Cassino ao Vivo
        if (($type === 'WinBet' || $type === 'game_callback') && $user->balance < $betAmount) {
            Log::error('PlayFiver Webhook Error: Saldo insuficiente para aposta', [
                'user' => $user->id,
                'balance' => $user->balance,
                'required' => $betAmount
            ]);
            return response()->json([
                'status' => 0,
                'msg' => 'Saldo insuficiente',
                'user_balance' => (float)number_format($user->balance, 2, '.', '')
            ]);
        }

        try {
            DB::transaction(function () use ($user, $betAmount, $winAmount, $gameCode, $type) {
                $oldBalance = (float)number_format($user->balance, 2, '.', '');
                
                // Cálculo exato
                $newBalance = $oldBalance - $betAmount + $winAmount;
                
                $user->balance = $newBalance;
                $user->save();

                // Registro da aposta para auditoria
                Bet::create([
                    'user_id' => $user->id,
                    'game' => $gameCode,
                    'bet_amount' => $betAmount,
                    'payout' => $winAmount,
                    'multiplier' => $betAmount > 0 ? ($winAmount / $betAmount) : 0,
                    'status' => $winAmount > 0 ? 'win' : 'lose',
                ]);
            });

            $finalBalance = (float)number_format($user->balance, 2, '.', '');
            Log::info("Callback Processado com Sucesso. Novo Saldo: {$finalBalance}");

            return response()->json([
                'status' => 1,
                'msg' => 'SUCCESS',
                'user_code' => $user->id,
                'user_balance' => $finalBalance,
                'balance' => $finalBalance,
                'txn_id' => $slotData['txn_id'] ?? ($data['txn_id'] ?? null)
            ]);
        } catch (\Exception $e) {
            Log::error('PlayFiver Webhook Processing Error: ' . $e->getMessage());
            return response()->json([
                'status' => 0,
                'msg' => 'Erro ao processar transação'
            ]);
        }
    }
}
