<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bet;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaxApiGamesController extends Controller
{
    /**
     * Handle MAX API GAMES Webhook Callback (Seamless mode).
     */
    public function handleCallback(Request $request)
    {
        Log::info('--- MAX API GAMES WEBHOOK INÍCIO ---');
        Log::info('IP: ' . $request->ip());
        Log::info('Payload:', $request->all());

        $data = $request->all();
        $method = $data['method'] ?? '';

        $expectedSecret = Setting::get('maxapigames_agent_secret');
        if ($expectedSecret && ($data['agent_secret'] ?? null) !== $expectedSecret) {
            Log::error('MAX API GAMES Webhook: agent_secret inválido');
            return response()->json(['status' => 0, 'msg' => 'Autenticação inválida']);
        }

        if ($method === 'user_balance') {
            $response = $this->getUserBalance($data);
            Log::info('MAX API GAMES Balance Response:', $response->getData(true));
            return $response;
        }

        if ($method === 'transaction') {
            $response = $this->processTransaction($data);
            Log::info('MAX API GAMES Transaction Response:', $response->getData(true));
            return $response;
        }

        Log::warning('MAX API GAMES Webhook Método Desconhecido:', ['method' => $method]);
        return response()->json(['status' => 0, 'msg' => 'Método inválido']);
    }

    /**
     * Return user balance to MAX API GAMES.
     */
    private function getUserBalance($data)
    {
        $userId = $data['user_code'] ?? null;
        $user = User::find($userId);

        if (!$user) {
            Log::error('MAX API GAMES Webhook Error: Usuário não encontrado', ['user_code' => $userId]);
            return response()->json(['status' => 0, 'msg' => 'Usuário não encontrado']);
        }

        return response()->json([
            'status' => 1,
            'user_balance' => (float)number_format($user->balance, 2, '.', '')
        ]);
    }

    /**
     * Process a bet/win transaction from MAX API GAMES.
     */
    private function processTransaction($data)
    {
        $userId = $data['user_code'] ?? null;
        $user = User::find($userId);

        if (!$user) {
            Log::error('MAX API GAMES Webhook Error: Usuário não encontrado', ['user_code' => $userId]);
            return response()->json(['status' => 0, 'msg' => 'Usuário não encontrado']);
        }

        $slot = $data['slot'] ?? [];
        $txnType = $slot['txn_type'] ?? 'debit_credit';
        $betAmount = (float)($slot['bet_money'] ?? 0);
        $winAmount = (float)($slot['win_money'] ?? 0);
        $gameCode = $slot['game_code'] ?? 'N/A';
        $txnId = $slot['txn_id'] ?? null;

        if ($txnType === 'credit') {
            $betAmount = 0;
        } elseif ($txnType === 'debit') {
            $winAmount = 0;
        }

        if ($betAmount > 0 && $user->balance < $betAmount) {
            Log::error('MAX API GAMES Webhook Error: Saldo insuficiente para aposta', [
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
            DB::transaction(function () use ($user, $betAmount, $winAmount, $gameCode, $txnId) {
                $oldBalance = (float)number_format($user->balance, 2, '.', '');
                $newBalance = $oldBalance - $betAmount + $winAmount;

                $user->balance = $newBalance;
                $user->save();

                Bet::create([
                    'user_id' => $user->id,
                    'game' => $gameCode,
                    'bet_amount' => $betAmount,
                    'payout' => $winAmount,
                    'multiplier' => $betAmount > 0 ? ($winAmount / $betAmount) : 0,
                    'status' => $winAmount > 0 ? 'win' : 'lose',
                    'transaction_id' => $txnId,
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // txn_id já processado (unique constraint) - responde com o saldo atual sem duplicar
            if ((int)$e->getCode() === 23000 || $e->getCode() === '23000') {
                Log::warning('MAX API GAMES Webhook: transação duplicada ignorada', ['txn_id' => $txnId]);
                $user->refresh();
                return response()->json([
                    'status' => 1,
                    'user_balance' => (float)number_format($user->balance, 2, '.', '')
                ]);
            }

            Log::error('MAX API GAMES Webhook Processing Error: ' . $e->getMessage());
            return response()->json(['status' => 0, 'msg' => 'Erro ao processar transação']);
        } catch (\Exception $e) {
            Log::error('MAX API GAMES Webhook Processing Error: ' . $e->getMessage());
            return response()->json(['status' => 0, 'msg' => 'Erro ao processar transação']);
        }

        $finalBalance = (float)number_format($user->balance, 2, '.', '');
        Log::info("MAX API GAMES Callback Processado com Sucesso. Novo Saldo: {$finalBalance}");

        return response()->json([
            'status' => 1,
            'user_balance' => $finalBalance
        ]);
    }
}
