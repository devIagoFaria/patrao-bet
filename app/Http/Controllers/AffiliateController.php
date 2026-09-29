<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AffiliateController extends Controller
{
    /**
     * Get affiliate statistics and lists for the dashboard.
     */
    public function getStats(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Não autorizado.'], 401);
        }

        $referredUserIds = User::where('referred_by', $user->id)->pluck('id')->toArray();
        $commissionPercent = floatval(Setting::get('referral_commission_percent', '10'));

        // --- HOJE (TODAY) STATS ---
        $todayRegistrations = User::where('referred_by', $user->id)
            ->whereDate('created_at', today())
            ->count();

        $todayDepositors = 0;
        $todayValidBets = 0.00;
        $todayCommission = 0.00;

        if (!empty($referredUserIds)) {
            // Count referred users whose first approved deposit is today
            $todayDepositors = Deposit::whereIn('user_id', $referredUserIds)
                ->where('status', 'approved')
                ->whereDate('created_at', today())
                ->whereNotIn('user_id', function($q) use ($referredUserIds) {
                    $q->select('user_id')
                      ->from('deposits')
                      ->whereIn('user_id', $referredUserIds)
                      ->where('status', 'approved')
                      ->whereDate('created_at', '<', today());
                })
                ->distinct('user_id')
                ->count();

            $todayValidBets = floatval(\App\Models\Bet::whereIn('user_id', $referredUserIds)
                ->whereDate('created_at', today())
                ->sum('amount'));

            $todayDepositsSum = Deposit::whereIn('user_id', $referredUserIds)
                ->where('status', 'approved')
                ->whereDate('created_at', today())
                ->sum('amount');
            $todayCommission = floatval($todayDepositsSum * ($commissionPercent / 100));
        }

        // --- TOTAL STATS ---
        $totalRegistrations = count($referredUserIds);
        $totalDepositors = 0;
        $totalValidBets = 0.00;
        $totalCommission = 0.00;

        if (!empty($referredUserIds)) {
            $totalDepositors = Deposit::whereIn('user_id', $referredUserIds)
                ->where('status', 'approved')
                ->distinct('user_id')
                ->count();

            $totalValidBets = floatval(\App\Models\Bet::whereIn('user_id', $referredUserIds)
                ->sum('amount'));

            $totalDepositsSum = Deposit::whereIn('user_id', $referredUserIds)
                ->where('status', 'approved')
                ->sum('amount');
            $totalCommission = floatval($totalDepositsSum * ($commissionPercent / 100));
        }

        // --- REFERÊNCIAS TAB LIST ---
        $referralsList = [];
        if (!empty($referredUserIds)) {
            $referredUsers = User::where('referred_by', $user->id)->get();
            foreach ($referredUsers as $index => $refUser) {
                $betsSum = \App\Models\Bet::where('user_id', $refUser->id)->sum('amount');
                $depositsSum = Deposit::where('user_id', $refUser->id)->where('status', 'approved')->sum('amount');
                $refCommission = $depositsSum * ($commissionPercent / 100);

                // Mask name or email
                $emailParts = explode('@', $refUser->email);
                $maskedAccount = substr($emailParts[0], 0, 3) . '***';
                if (isset($emailParts[1])) {
                    $maskedAccount .= '@' . $emailParts[1];
                }

                $referralsList[] = [
                    'index' => $index + 1,
                    'account' => $maskedAccount,
                    'bets' => number_format($betsSum, 2, ',', '.'),
                    'team_bets' => '0,00',
                    'commission' => number_format($refCommission, 2, ',', '.'),
                    'registered_at' => $refUser->created_at->format('d/m/Y H:i'),
                ];
            }
        }

        // --- RELATÓRIO TAB LIST (last 30 days) ---
        $reportsList = [];
        for ($i = 29; $i >= 0; $i--) {
            $dateObj = now()->subDays($i);
            $dateStr = $dateObj->format('Y-m-d');
            $dateDisplay = $dateObj->format('d/m/Y');

            $dayRegistrations = User::where('referred_by', $user->id)
                ->whereDate('created_at', $dateStr)
                ->count();

            $dayBets = 0.00;
            $dayCommission = 0.00;

            if (!empty($referredUserIds)) {
                $dayBets = floatval(\App\Models\Bet::whereIn('user_id', $referredUserIds)
                    ->whereDate('created_at', $dateStr)
                    ->sum('amount'));

                $dayDepositsSum = Deposit::whereIn('user_id', $referredUserIds)
                    ->where('status', 'approved')
                    ->whereDate('created_at', $dateStr)
                    ->sum('amount');
                $dayCommission = floatval($dayDepositsSum * ($commissionPercent / 100));
            }

            if ($dayRegistrations > 0 || $dayBets > 0 || $dayCommission > 0) {
                $reportsList[] = [
                    'date' => $dateDisplay,
                    'registrations' => $dayRegistrations,
                    'valid_bets' => number_format($dayBets, 2, ',', '.'),
                    'agent_level' => 'Bronze 1',
                    'commission' => number_format($dayCommission, 2, ',', '.'),
                ];
            }
        }

        // --- PAGAMENTOS TAB LIST (Transfers history) ---
        $paymentsList = [];
        $notifications = Notification::where('user_id', $user->id)
            ->where('title', 'Transferência de Comissão de Afiliado')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($notifications as $index => $notif) {
            preg_match('/R\$ ([\d,.]+)/', $notif->message, $matches);
            $amountStr = $matches[1] ?? '0,00';
            $paymentsList[] = [
                'index' => $index + 1,
                'date' => $notif->created_at->format('d/m/Y H:i'),
                'detail' => 'Transferência para Carteira',
                'commission' => $amountStr,
                'status' => 'Sucesso',
            ];
        }

        // Render response data
        return response()->json([
            'success' => true,
            'today' => [
                'registrations' => $todayRegistrations,
                'new_players' => $todayDepositors,
                'valid_bets' => number_format($todayValidBets, 2, ',', '.'),
                'commission' => number_format($todayCommission, 2, ',', '.'),
            ],
            'total' => [
                'registrations' => $totalRegistrations,
                'total_players' => $totalDepositors,
                'valid_bets' => number_format($totalValidBets, 2, ',', '.'),
                'commission' => number_format($totalCommission, 2, ',', '.'),
            ],
            'unpaid_commission' => number_format($user->affiliate_commission_unpaid, 2, ',', '.'),
            'paid_commission' => number_format($user->affiliate_commission_paid, 2, ',', '.'),
            'referral_link' => url('/?ref=' . $user->id),
            'referrals' => $referralsList,
            'reports' => $reportsList,
            'payments' => $paymentsList,
        ]);
    }

    /**
     * Transfer unpaid affiliate commission to standard balance.
     */
    public function transferCommission(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Não autorizado.'], 401);
        }

        $unpaid = floatval($user->affiliate_commission_unpaid);
        if ($unpaid < 10.00) {
            return response()->json([
                'success' => false,
                'message' => 'O valor mínimo para transferência de comissão é R$ 10,00.'
            ], 400);
        }

        try {
            DB::transaction(function() use ($user, $unpaid) {
                // Deduct from unpaid, add to paid
                $user->affiliate_commission_unpaid = 0.00;
                $user->affiliate_commission_paid += $unpaid;
                
                // Credit to standard playable balance
                $user->balance += $unpaid;
                $user->save();

                // Generate notification log
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Transferência de Comissão de Afiliado',
                    'message' => "Você transferiu com sucesso R$ " . number_format($unpaid, 2, ',', '.') . " da sua carteira de afiliados para o seu saldo principal.",
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Comissão de R$ ' . number_format($unpaid, 2, ',', '.') . ' transferida para sua carteira principal!',
                'new_balance' => number_format($user->balance, 2, ',', '.'),
                'unpaid_commission' => '0,00',
                'paid_commission' => number_format($user->affiliate_commission_paid, 2, ',', '.'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao processar transferência: ' . $e->getMessage()
            ], 500);
        }
    }
}
