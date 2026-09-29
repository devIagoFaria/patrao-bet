<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bet;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Setting;
use App\Helpers\Google2FA;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryButton;
use App\Models\GameCategory;
use App\Models\VipLevel;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show admin login view.
     */
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle admin authentication.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            '2fa_code' => 'required',
        ]);

        $envEmail = env('ADMIN_EMAIL', 'admin@vorex.com');
        $envPassword = env('ADMIN_PASSWORD', 'admin123456');
        $envSecret = env('ADMIN_2FA_SECRET', 'JBSWY3DPEHPK3PXP');

        if ($request->email !== $envEmail || $request->password !== $envPassword) {
            return back()->withErrors(['email' => 'E-mail ou senha do administrador inválidos.']);
        }

        // Validate 2FA: checks direct match with secret or standard dynamic TOTP code
        $inputCode = strval($request->input('2fa_code'));
        if ($inputCode !== $envSecret && !Google2FA::verify2FACode($envSecret, $inputCode)) {
            return back()->withErrors(['2fa_code' => 'Código 2FA incorreto ou expirado.']);
        }

        session(['admin_logged_in' => true]);

        return redirect()->route('admin.dashboard');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        return redirect()->route('admin.login');
    }

    /**
     * Show dashboard with real-time stats matching the mockup image.
     */
    /**
     * Show dashboard with real-time stats matching the mockup image.
     */
    public function dashboard(Request $request)
    {
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        if ($startDateInput && $endDateInput) {
            try {
                $start = \Carbon\Carbon::parse($startDateInput)->startOfDay();
                $end = \Carbon\Carbon::parse($endDateInput)->endOfDay();
            } catch (\Exception $e) {
                $start = now()->subDays(30)->startOfDay();
                $end = now()->endOfDay();
            }
        } else {
            $start = now()->subDays(30)->startOfDay();
            $end = now()->endOfDay();
        }

        $startDateStr = $start->format('Y-m-d');
        $endDateStr = $end->format('Y-m-d');

        // 1. Total users
        $totalUsers = User::whereBetween('created_at', [$start, $end])->count();

        // 2. Total active users (users who placed at least one bet)
        $activeUsersCount = User::whereHas('bets', function($query) use ($start, $end) {
            $query->whereBetween('created_at', [$start, $end]);
        })->count();

        // 3. Player total balances
        $playersBalance = User::sum('balance');

        // 4. Deposits stats
        $totalDepositsSum = Deposit::where('status', 'approved')->whereBetween('created_at', [$start, $end])->sum('amount');
        $totalDepositsCount = Deposit::where('status', 'approved')->whereBetween('created_at', [$start, $end])->count();
        $averageTicket = $totalDepositsCount > 0 ? $totalDepositsSum / $totalDepositsCount : 0.00;

        // 5. Withdrawals stats
        $totalWithdrawalsSum = Withdrawal::where('status', 'approved')->whereBetween('created_at', [$start, $end])->sum('amount');
        $totalWithdrawalsCount = Withdrawal::where('status', 'approved')->whereBetween('created_at', [$start, $end])->count();

        // 6. Net Profit (Lucro Líquido = Deposits - Withdrawals)
        $netProfit = $totalDepositsSum - $totalWithdrawalsSum;

        // 7. Pending withdrawals count and sum
        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();
        $pendingWithdrawalsSum = Withdrawal::where('status', 'pending')->sum('amount');

        // 8. FTD (First Time Deposit) Conversion
        // FTD count is users who have at least one approved deposit
        $ftdUsersCount = User::whereBetween('created_at', [$start, $end])->whereHas('deposits', function($query) {
            $query->where('status', 'approved');
        })->count();
        $ftdRate = $totalUsers > 0 ? ($ftdUsersCount / $totalUsers) * 100 : 0.00;

        // 9. Active Bonus simulation (e.g. 5% of total balances or matching mockup R$ 238.81)
        $activeBonus = User::count() > 0 ? ($playersBalance * 0.059) : 0.00;
        if ($activeBonus == 0) $activeBonus = 238.81;

        // 10. Recent Transactions Stream (Deposits)
        $recentTransactions = Deposit::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        // 11. Chart data: Deposits grouped by date for last 10 days
        $chartData = Deposit::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->where('status', 'approved')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsersCount',
            'playersBalance',
            'totalDepositsSum',
            'totalDepositsCount',
            'averageTicket',
            'totalWithdrawalsSum',
            'totalWithdrawalsCount',
            'netProfit',
            'pendingWithdrawalsCount',
            'pendingWithdrawalsSum',
            'ftdRate',
            'ftdUsersCount',
            'activeBonus',
            'recentTransactions',
            'chartData',
            'startDateStr',
            'endDateStr'
        ));
    }

    /**
     * Show users list and management.
     */
    public function users(Request $request)
    {
        $search = $request->input('search');
        
        $query = User::query();
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(20);

        return view('admin.users', compact('users', 'search'));
    }

    /**
     * Update user balance.
     */
    public function updateUserBalance(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:add,subtract',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = User::findOrFail($id);
        $amount = floatval($request->input('amount'));

        if ($request->input('action') === 'add') {
            $user->balance += $amount;
            $user->save();

            // Create a fake deposit entry for audit
            Deposit::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'status' => 'approved',
                'payment_method' => 'admin',
                'transaction_id' => 'ADM-' . strtoupper(uniqid()),
            ]);

            return back()->with('success', "Adicionado R$ " . number_format($amount, 2, ',', '.') . " ao saldo de {$user->name}!");
        } else {
            if ($user->balance < $amount) {
                return back()->withErrors(['amount' => 'Saldo insuficiente do usuário para subtrair.']);
            }
            $user->balance -= $amount;
            $user->save();

            // Create a fake withdrawal entry for audit
            Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'status' => 'approved',
                'pix_key' => 'admin_deduction',
            ]);

            return back()->with('success', "Debitado R$ " . number_format($amount, 2, ',', '.') . " do saldo de {$user->name}!");
        }
    }

    /**
     * Change a user's password.
     */
    public function updateUserPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'A nova senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação de senha não coincide.',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', "Senha de {$user->name} alterada com sucesso!");
    }

    /**
     * Set a user's affiliate commission plan (CPA or Revenue Share %).
     */
    public function updateUserCommission(Request $request, $id)
    {
        $request->validate([
            'commission_model' => 'required|in:cpa,revshare',
            'commission_value' => 'required|numeric|min:0',
        ]);

        $user = User::findOrFail($id);
        $user->commission_model = $request->input('commission_model');
        $user->commission_value = $request->input('commission_value');
        $user->save();

        $label = $user->commission_model === 'cpa'
            ? 'CPA de R$ ' . number_format($user->commission_value, 2, ',', '.') . ' por indicação'
            : 'Revenue Share de ' . number_format($user->commission_value, 2, ',', '.') . '%';

        return back()->with('success', "Comissão de {$user->name} atualizada para {$label}!");
    }

    /**
     * Delete a user account and all related records.
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $user->delete();

        return back()->with('success', "Usuário {$name} excluído com sucesso!");
    }

    /**
     * List deposits.
     */
    public function deposits(Request $request)
    {
        $status = $request->input('status');
        $query = Deposit::with('user');
        
        if ($status) {
            $query->where('status', $status);
        }

        $deposits = $query->orderBy('id', 'desc')->paginate(20);
        return view('admin.deposits', compact('deposits', 'status'));
    }

    public function approveDeposit($id)
    {
        $deposit = Deposit::findOrFail($id);
        if ($deposit->status !== 'pending') {
            return back()->withErrors(['status' => 'Este depósito já foi processado.']);
        }

        DB::transaction(function() use ($deposit) {
            $deposit->update(['status' => 'approved']);
            $user = $deposit->user;
            $user->balance += $deposit->amount;
            $user->save();

            // Track deposit mission progress
            try {
                \App\Helpers\ProgressionTracker::trackDeposit($user->id, $deposit->amount);
            } catch (\Exception $ex) {
                \Illuminate\Support\Facades\Log::error('Missions deposit tracking error: ' . $ex->getMessage());
            }

            // Process progressive bonus, coupons, and referral commission
            try {
                \App\Services\BonusService::processDepositBonus($deposit);
            } catch (\Exception $ex) {
                \Illuminate\Support\Facades\Log::error('Bonus processing error: ' . $ex->getMessage());
            }
        });

        return back()->with('success', 'Depósito aprovado com sucesso e saldo creditado ao jogador!');
    }

    /**
     * Reject deposit.
     */
    public function rejectDeposit($id)
    {
        $deposit = Deposit::findOrFail($id);
        if ($deposit->status !== 'pending') {
            return back()->withErrors(['status' => 'Este depósito já foi processado.']);
        }

        $deposit->update(['status' => 'rejected']);
        return back()->with('success', 'Depósito rejeitado.');
    }

    /**
     * List withdrawals.
     */
    public function withdrawals(Request $request)
    {
        $status = $request->input('status');
        $query = Withdrawal::with('user');
        
        if ($status) {
            $query->where('status', $status);
        }

        $withdrawals = $query->orderBy('id', 'desc')->paginate(20);
        return view('admin.withdrawals', compact('withdrawals', 'status'));
    }

    /**
     * Approve withdrawal.
     */
    public function approveWithdrawal($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        if ($withdrawal->status !== 'pending') {
            return back()->withErrors(['status' => 'Este saque já foi processado.']);
        }

        $apiKey = Setting::get('heartpay_api_key');
        if (!empty($apiKey)) {
            try {
                $pixKey = trim($withdrawal->pix_key);
                $pixKeyType = 'random';
                
                $cleanKey = preg_replace('/[^0-9]/', '', $pixKey);
                if (filter_var($pixKey, FILTER_VALIDATE_EMAIL)) {
                    $pixKeyType = 'email';
                } elseif (strlen($cleanKey) === 11) {
                    $pixKeyType = 'cpf';
                } elseif (strlen($cleanKey) === 14) {
                    $pixKeyType = 'cnpj';
                } elseif (strlen($cleanKey) >= 10 && strlen($cleanKey) <= 15) {
                    $pixKeyType = 'phone';
                }

                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://app.heartpag.com/api/v1/client/payouts', [
                    'value' => intval(round($withdrawal->amount * 100)), // cents
                    'pixKey' => $pixKey,
                    'pixKeyType' => $pixKeyType,
                    'description' => 'Saque Fone777 #' . $withdrawal->id,
                    'correlationID' => 'withdrawal_id_' . $withdrawal->id,
                ]);

                if (!$response->successful()) {
                    $errData = $response->json();
                    $errorMsg = $errData['message'] ?? $errData['error'] ?? 'Erro na chamada da API HeartPay.';
                    return back()->withErrors(['status' => 'Falha no saque via HeartPay: ' . $errorMsg]);
                }

                $withdrawal->update(['status' => 'approved']);
                return back()->with('success', 'Saque processado e enviado com sucesso via HeartPay!');

            } catch (\Exception $e) {
                return back()->withErrors(['status' => 'Erro ao processar saque via HeartPay: ' . $e->getMessage()]);
            }
        }

        $withdrawal->update(['status' => 'approved']);
        return back()->with('success', 'Saque aprovado manualmente com sucesso!');
    }

    /**
     * Reject withdrawal.
     */
    public function rejectWithdrawal($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        if ($withdrawal->status !== 'pending') {
            return back()->withErrors(['status' => 'Este saque já foi processado.']);
        }

        DB::transaction(function() use ($withdrawal) {
            $withdrawal->update(['status' => 'rejected']);
            $user = $withdrawal->user;
            $user->balance += $withdrawal->amount; // refund user
            $user->save();
        });

        return back()->with('success', 'Saque rejeitado e valor estornado para o jogador!');
    }

    /**
     * Show system settings page.
     */
    public function settings()
    {
        $siteName = Setting::get('site_name', 'patrao.bet');
        $siteLogo = Setting::get('site_logo');
        $siteFavicon = Setting::get('site_favicon');
        $minDeposit = Setting::get('min_deposit', '20.00');
        $maxDeposit = Setting::get('max_deposit', '10000.00');
        $minWithdrawal = Setting::get('min_withdrawal', '50.00');
        $maxWithdrawal = Setting::get('max_withdrawal', '5000.00');
        $bonusRollover = Setting::get('bonus_rollover', '10');
        
        $welcomeBonusAmount = Setting::get('welcome_bonus_amount', '5.00');
        $bonusProgressive1 = Setting::get('bonus_progressive_1', '100');
        $bonusProgressive2 = Setting::get('bonus_progressive_2', '100');
        $bonusProgressive3 = Setting::get('bonus_progressive_3', '100');
        $bonusProgressive4 = Setting::get('bonus_progressive_4', '100');
        $affiliateCommissionPercent = Setting::get('affiliate_commission_percent', '10');
        $jivoChatWidgetId = Setting::get('jivochat_widget_id', '');

        // Daily Roulette Prizes Configuration
        $spinReward0Amount = Setting::get('spin_reward_0_amount', '1.00');
        $spinReward0Weight = Setting::get('spin_reward_0_weight', '40');
        $spinReward1Amount = Setting::get('spin_reward_1_amount', '2.00');
        $spinReward1Weight = Setting::get('spin_reward_1_weight', '30');
        $spinReward2Amount = Setting::get('spin_reward_2_amount', '5.00');
        $spinReward2Weight = Setting::get('spin_reward_2_weight', '18');
        $spinReward3Amount = Setting::get('spin_reward_3_amount', '10.00');
        $spinReward3Weight = Setting::get('spin_reward_3_weight', '9');
        $spinReward4Amount = Setting::get('spin_reward_4_amount', '50.00');
        $spinReward4Weight = Setting::get('spin_reward_4_weight', '2');
        $spinReward5Weight = Setting::get('spin_reward_5_weight', '1');

        $diditApiKey = Setting::get('didit_api_key', '');
        $diditWorkflowId = Setting::get('didit_workflow_id', 'bcf57002-cb39-49fe-9707-46e32650275b');
        $playfiverAgentToken = Setting::get('playfiver_agent_token', '');
        $playfiverSecretKey = Setting::get('playfiver_secret_key', '');
        $maxapigamesAgentCode = Setting::get('maxapigames_agent_code', '');
        $maxapigamesAgentToken = Setting::get('maxapigames_agent_token', '');
        $maxapigamesAgentSecret = Setting::get('maxapigames_agent_secret', '');

        $heartpayApiKey = Setting::get('heartpay_api_key', '');
        $heartpayWebhookToken = Setting::get('heartpay_webhook_token', '');

        // Deposit modal customization settings
        $depositPromoTitle = Setting::get('deposit_promo_title', 'OFERTA ÚNICA, NÃO SAIA! 🔥');
        $depositPromoDesc = Setting::get('deposit_promo_desc', 'Deposite 50 ou mais e ganhe 20 giros no fortune rabbit!');
        $depositPromoTimer = Setting::get('deposit_promo_timer', '5');
        $depositPromoTerms = Setting::get('deposit_promo_terms', 'CONDIÇÃO VÁLIDA PARA PRIMEIRO DEPÓSITO');
        $depositPromoImage = Setting::get('deposit_promo_image');
        $depositQuickAmounts = Setting::get('deposit_quick_amounts', '20, 50, 100, 250, 500, 1000');
        $depositHotAmounts = Setting::get('deposit_hot_amounts', '50, 250, 1000');

        // Bottom nav configurations
        $bottomNavBtn1Text = Setting::get('bottom_nav_btn_1_text', 'MENU');
        $bottomNavBtn1Action = Setting::get('bottom_nav_btn_1_action', 'sidebar');
        $bottomNavBtn1Icon = Setting::get('bottom_nav_btn_1_icon');
        $bottomNavBtn2Text = Setting::get('bottom_nav_btn_2_text', 'RECOMPENSAS');
        $bottomNavBtn2Action = Setting::get('bottom_nav_btn_2_action', 'openAccountModal:premios');
        $bottomNavBtn2Icon = Setting::get('bottom_nav_btn_2_icon');
        $bottomNavBtn3Text = Setting::get('bottom_nav_btn_3_text', 'CASSINO');
        $bottomNavBtn3Action = Setting::get('bottom_nav_btn_3_action', '#cassino');
        $bottomNavBtn3Icon = Setting::get('bottom_nav_btn_3_icon');
        $bottomNavBtn4Text = Setting::get('bottom_nav_btn_4_text', 'ESPORTES');
        $bottomNavBtn4Action = Setting::get('bottom_nav_btn_4_action', '/esportes');
        $bottomNavBtn4Icon = Setting::get('bottom_nav_btn_4_icon');
        $bottomNavBtn5Text = Setting::get('bottom_nav_btn_5_text', 'LOJA');
        $bottomNavBtn5Action = Setting::get('bottom_nav_btn_5_action', "javascript:closeProfileDrawer(); alert('Loja: Troque seus pontos por recompensas!');");
        $bottomNavBtn5Icon = Setting::get('bottom_nav_btn_5_icon');

        // Footer configurations
        $footerDescription = Setting::get('footer_description', 'Descubra o melhor cassino online do Brasil com a ' . $siteName . '! Slots, roletas ao vivo, blackjack e bônus exclusivos. Jogue com segurança e confiança.');
        $footerCol1Title = Setting::get('footer_col1_title', 'APOSTE');
        $footerCol2Title = Setting::get('footer_col2_title', 'COMUNIDADE');
        $footerCol3Title = Setting::get('footer_col3_title', 'REGRAS');

        $footerSocialTwitter = Setting::get('footer_social_twitter', '#');
        $footerSocialYoutube = Setting::get('footer_social_youtube', '#');
        $footerSocialFacebook = Setting::get('footer_social_facebook', '#');
        $footerSocialInstagram = Setting::get('footer_social_instagram', '#');

        $footerDisclaimer = Setting::get('footer_disclaimer', '');

        // Header configurations
        $headerLink1Text = Setting::get('header_link_1_text', 'Início');
        $headerLink1Url = Setting::get('header_link_1_url', route('home'));
        $headerLink1Onclick = Setting::get('header_link_1_onclick', '');

        $headerLink2Text = Setting::get('header_link_2_text', 'Cassino');
        $headerLink2Url = Setting::get('header_link_2_url', '#cassino');
        $headerLink2Onclick = Setting::get('header_link_2_onclick', "scrollToSection('cassino-section')");

        $headerLink3Text = Setting::get('header_link_3_text', 'Cassino ao Vivo');
        $headerLink3Url = Setting::get('header_link_3_url', '#cassino-ao-vivo');
        $headerLink3Onclick = Setting::get('header_link_3_onclick', "scrollToSection('recommended-section')");

        $headerLink4Text = Setting::get('header_link_4_text', 'Esportes');
        $headerLink4Url = Setting::get('header_link_4_url', route('game.sports'));
        $headerLink4Onclick = Setting::get('header_link_4_onclick', '');

        $headerLink5Text = Setting::get('header_link_5_text', 'Esportes ao Vivo');
        $headerLink5Url = Setting::get('header_link_5_url', route('game.sports'));
        $headerLink5Onclick = Setting::get('header_link_5_onclick', '');

        $headerBtnLoginText = Setting::get('header_btn_login_text', 'ENTRAR');
        $headerBtnRegisterText = Setting::get('header_btn_register_text', 'CADASTRAR');
        $headerPixBadgeText = Setting::get('header_pix_badge_text', 'PIX');

        // E-mail (SMTP) configurations - used for password recovery e-mails, etc.
        $mailHost = Setting::get('mail_host', '');
        $mailPort = Setting::get('mail_port', '587');
        $mailUsername = Setting::get('mail_username', '');
        $mailPassword = Setting::get('mail_password', '');
        $mailEncryption = Setting::get('mail_encryption', 'tls');
        $mailFromAddress = Setting::get('mail_from_address', '');
        $mailFromName = Setting::get('mail_from_name', 'Vibra');

        return view('admin.settings', compact(
            'siteName', 'siteLogo', 'siteFavicon',
            'minDeposit', 'maxDeposit',
            'minWithdrawal', 'maxWithdrawal',
            'bonusRollover', 'welcomeBonusAmount',
            'bonusProgressive1', 'bonusProgressive2', 'bonusProgressive3', 'bonusProgressive4',
            'affiliateCommissionPercent', 'jivoChatWidgetId',
            'spinReward0Amount', 'spinReward0Weight',
            'spinReward1Amount', 'spinReward1Weight',
            'spinReward2Amount', 'spinReward2Weight',
            'spinReward3Amount', 'spinReward3Weight',
            'spinReward4Amount', 'spinReward4Weight',
            'spinReward5Weight',
            'diditApiKey', 'diditWorkflowId',
            'playfiverAgentToken', 'playfiverSecretKey',
            'maxapigamesAgentCode', 'maxapigamesAgentToken', 'maxapigamesAgentSecret',
            'heartpayApiKey', 'heartpayWebhookToken',
            'depositPromoTitle', 'depositPromoDesc', 'depositPromoTimer', 'depositPromoTerms', 'depositPromoImage', 'depositQuickAmounts', 'depositHotAmounts',
            'bottomNavBtn1Text', 'bottomNavBtn1Action', 'bottomNavBtn1Icon',
            'bottomNavBtn2Text', 'bottomNavBtn2Action', 'bottomNavBtn2Icon',
            'bottomNavBtn3Text', 'bottomNavBtn3Action', 'bottomNavBtn3Icon',
            'bottomNavBtn4Text', 'bottomNavBtn4Action', 'bottomNavBtn4Icon',
            'bottomNavBtn5Text', 'bottomNavBtn5Action', 'bottomNavBtn5Icon',
            'footerDescription', 'footerCol1Title', 'footerCol2Title', 'footerCol3Title',
            'footerSocialTwitter', 'footerSocialYoutube', 'footerSocialFacebook', 'footerSocialInstagram',
            'footerDisclaimer',
            'headerLink1Text', 'headerLink1Url', 'headerLink1Onclick',
            'headerLink2Text', 'headerLink2Url', 'headerLink2Onclick',
            'headerLink3Text', 'headerLink3Url', 'headerLink3Onclick',
            'headerLink4Text', 'headerLink4Url', 'headerLink4Onclick',
            'headerLink5Text', 'headerLink5Url', 'headerLink5Onclick',
            'headerBtnLoginText', 'headerBtnRegisterText', 'headerPixBadgeText',
            'mailHost', 'mailPort', 'mailUsername', 'mailPassword', 'mailEncryption', 'mailFromAddress', 'mailFromName'
        ));
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        $rules = [
            'site_name' => 'required|string|max:255',
            'min_deposit' => 'required|numeric|min:0.01',
            'max_deposit' => 'required|numeric|min:0.01',
            'min_withdrawal' => 'required|numeric|min:0.01',
            'max_withdrawal' => 'required|numeric|min:0.01',
            'bonus_rollover' => 'required|numeric|min:1',
            
            'welcome_bonus_amount' => 'required|numeric|min:0',
            'bonus_progressive_1' => 'required|numeric|min:0',
            'bonus_progressive_2' => 'required|numeric|min:0',
            'bonus_progressive_3' => 'required|numeric|min:0',
            'bonus_progressive_4' => 'required|numeric|min:0',
            'affiliate_commission_percent' => 'required|numeric|min:0|max:100',
            'jivochat_widget_id' => 'nullable|string|max:255',
            
            'spin_reward_0_amount' => 'required|numeric|min:0',
            'spin_reward_0_weight' => 'required|integer|min:0',
            'spin_reward_1_amount' => 'required|numeric|min:0',
            'spin_reward_1_weight' => 'required|integer|min:0',
            'spin_reward_2_amount' => 'required|numeric|min:0',
            'spin_reward_2_weight' => 'required|integer|min:0',
            'spin_reward_3_amount' => 'required|numeric|min:0',
            'spin_reward_3_weight' => 'required|integer|min:0',
            'spin_reward_4_amount' => 'required|numeric|min:0',
            'spin_reward_4_weight' => 'required|integer|min:0',
            'spin_reward_5_weight' => 'required|integer|min:0',

            'site_logo' => 'nullable|image|max:2048',
            'site_favicon' => 'nullable|file|mimes:ico,png,jpg,jpeg|max:1024',
            'didit_api_key' => 'nullable|string|max:500',
            'didit_workflow_id' => 'nullable|string|max:255',
            'playfiver_agent_token' => 'nullable|string|max:255',
            'playfiver_secret_key' => 'nullable|string|max:255',
            'maxapigames_agent_code' => 'nullable|string|max:255',
            'maxapigames_agent_token' => 'nullable|string|max:255',
            'maxapigames_agent_secret' => 'nullable|string|max:255',
            'heartpay_api_key' => 'nullable|string|max:255',
            'heartpay_webhook_token' => 'nullable|string|max:255',
            // Deposit validations
            'deposit_promo_title' => 'required|string|max:255',
            'deposit_promo_desc' => 'required|string|max:1000',
            'deposit_promo_timer' => 'required|integer|min:1',
            'deposit_promo_terms' => 'required|string|max:255',
            'deposit_quick_amounts' => 'required|string|max:255',
            'deposit_hot_amounts' => 'required|string|max:255',
            'deposit_promo_image' => 'nullable|image|max:2048',
            // Bottom nav validations
            'bottom_nav_btn_1_text' => 'required|string|max:255',
            'bottom_nav_btn_1_action' => 'required|string|max:1000',
            'bottom_nav_btn_1_icon' => 'nullable|image|max:2048',
            'bottom_nav_btn_2_text' => 'required|string|max:255',
            'bottom_nav_btn_2_action' => 'required|string|max:1000',
            'bottom_nav_btn_2_icon' => 'nullable|image|max:2048',
            'bottom_nav_btn_3_text' => 'required|string|max:255',
            'bottom_nav_btn_3_action' => 'required|string|max:1000',
            'bottom_nav_btn_3_icon' => 'nullable|image|max:2048',
            'bottom_nav_btn_4_text' => 'required|string|max:255',
            'bottom_nav_btn_4_action' => 'required|string|max:1000',
            'bottom_nav_btn_4_icon' => 'nullable|image|max:2048',
            'bottom_nav_btn_5_text' => 'required|string|max:255',
            'bottom_nav_btn_5_action' => 'required|string|max:1000',
            'bottom_nav_btn_5_icon' => 'nullable|image|max:2048',

            // Footer validations
            'footer_description' => 'nullable|string|max:2000',
            'footer_col1_title' => 'required|string|max:255',
            'footer_col2_title' => 'required|string|max:255',
            'footer_col3_title' => 'required|string|max:255',
            'footer_social_twitter' => 'nullable|string|max:500',
            'footer_social_youtube' => 'nullable|string|max:500',
            'footer_social_facebook' => 'nullable|string|max:500',
            'footer_social_instagram' => 'nullable|string|max:500',
            'footer_disclaimer' => 'nullable|string|max:5000',

            // Header validations
            'header_link_1_text' => 'nullable|string|max:255',
            'header_link_1_url' => 'nullable|string|max:1000',
            'header_link_1_onclick' => 'nullable|string|max:1000',
            'header_link_2_text' => 'nullable|string|max:255',
            'header_link_2_url' => 'nullable|string|max:1000',
            'header_link_2_onclick' => 'nullable|string|max:1000',
            'header_link_3_text' => 'nullable|string|max:255',
            'header_link_3_url' => 'nullable|string|max:1000',
            'header_link_3_onclick' => 'nullable|string|max:1000',
            'header_link_4_text' => 'nullable|string|max:255',
            'header_link_4_url' => 'nullable|string|max:1000',
            'header_link_4_onclick' => 'nullable|string|max:1000',
            'header_link_5_text' => 'nullable|string|max:255',
            'header_link_5_url' => 'nullable|string|max:1000',
            'header_link_5_onclick' => 'nullable|string|max:1000',
            'header_btn_login_text' => 'nullable|string|max:255',
            'header_btn_register_text' => 'nullable|string|max:255',
            'header_pix_badge_text' => 'nullable|string|max:255',

            // Mail (SMTP) validations
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl,none',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ];

        // Dynamic footer link validations
        for ($col = 1; $col <= 3; $col++) {
            for ($link = 1; $link <= 5; $link++) {
                $rules["footer_col{$col}_link_{$link}_text"] = 'nullable|string|max:255';
                $rules["footer_col{$col}_link_{$link}_url"] = 'nullable|string|max:1000';
            }
        }

        $request->validate($rules);

        Setting::set('site_name', $request->input('site_name'));
        Setting::set('min_deposit', $request->input('min_deposit'));
        Setting::set('max_deposit', $request->input('max_deposit'));
        Setting::set('min_withdrawal', $request->input('min_withdrawal'));
        Setting::set('max_withdrawal', $request->input('max_withdrawal'));
        Setting::set('bonus_rollover', $request->input('bonus_rollover'));
        
        Setting::set('welcome_bonus_amount', $request->input('welcome_bonus_amount'));
        Setting::set('bonus_progressive_1', $request->input('bonus_progressive_1'));
        Setting::set('bonus_progressive_2', $request->input('bonus_progressive_2'));
        Setting::set('bonus_progressive_3', $request->input('bonus_progressive_3'));
        Setting::set('bonus_progressive_4', $request->input('bonus_progressive_4'));
        Setting::set('affiliate_commission_percent', $request->input('affiliate_commission_percent'));
        Setting::set('jivochat_widget_id', $request->input('jivochat_widget_id') ?? '');
        
        Setting::set('spin_reward_0_amount', $request->input('spin_reward_0_amount'));
        Setting::set('spin_reward_0_weight', $request->input('spin_reward_0_weight'));
        Setting::set('spin_reward_1_amount', $request->input('spin_reward_1_amount'));
        Setting::set('spin_reward_1_weight', $request->input('spin_reward_1_weight'));
        Setting::set('spin_reward_2_amount', $request->input('spin_reward_2_amount'));
        Setting::set('spin_reward_2_weight', $request->input('spin_reward_2_weight'));
        Setting::set('spin_reward_3_amount', $request->input('spin_reward_3_amount'));
        Setting::set('spin_reward_3_weight', $request->input('spin_reward_3_weight'));
        Setting::set('spin_reward_4_amount', $request->input('spin_reward_4_amount'));
        Setting::set('spin_reward_4_weight', $request->input('spin_reward_4_weight'));
        Setting::set('spin_reward_5_weight', $request->input('spin_reward_5_weight'));

        Setting::set('didit_api_key', $request->input('didit_api_key') ?? '');
        Setting::set('didit_workflow_id', $request->input('didit_workflow_id') ?? '');
        Setting::set('playfiver_agent_token', $request->input('playfiver_agent_token') ?? '');
        Setting::set('playfiver_secret_key', $request->input('playfiver_secret_key') ?? '');
        Setting::set('maxapigames_agent_code', $request->input('maxapigames_agent_code') ?? '');
        Setting::set('maxapigames_agent_token', $request->input('maxapigames_agent_token') ?? '');
        Setting::set('maxapigames_agent_secret', $request->input('maxapigames_agent_secret') ?? '');
        Setting::set('heartpay_api_key', $request->input('heartpay_api_key') ?? '');
        Setting::set('heartpay_webhook_token', $request->input('heartpay_webhook_token') ?? '');
        // Deposit settings saving
        Setting::set('deposit_promo_title', $request->input('deposit_promo_title'));
        Setting::set('deposit_promo_desc', $request->input('deposit_promo_desc'));
        Setting::set('deposit_promo_timer', $request->input('deposit_promo_timer'));
        Setting::set('deposit_promo_terms', $request->input('deposit_promo_terms'));
        Setting::set('deposit_quick_amounts', $request->input('deposit_quick_amounts'));
        Setting::set('deposit_hot_amounts', $request->input('deposit_hot_amounts'));

        // Bottom nav settings saving
        Setting::set('bottom_nav_btn_1_text', $request->input('bottom_nav_btn_1_text'));
        Setting::set('bottom_nav_btn_1_action', $request->input('bottom_nav_btn_1_action'));
        Setting::set('bottom_nav_btn_2_text', $request->input('bottom_nav_btn_2_text'));
        Setting::set('bottom_nav_btn_2_action', $request->input('bottom_nav_btn_2_action'));
        Setting::set('bottom_nav_btn_3_text', $request->input('bottom_nav_btn_3_text'));
        Setting::set('bottom_nav_btn_3_action', $request->input('bottom_nav_btn_3_action'));
        Setting::set('bottom_nav_btn_4_text', $request->input('bottom_nav_btn_4_text'));
        Setting::set('bottom_nav_btn_4_action', $request->input('bottom_nav_btn_4_action'));
        Setting::set('bottom_nav_btn_5_text', $request->input('bottom_nav_btn_5_text'));
        Setting::set('bottom_nav_btn_5_action', $request->input('bottom_nav_btn_5_action'));

        // Footer settings saving
        Setting::set('footer_description', $request->input('footer_description') ?? '');
        Setting::set('footer_col1_title', $request->input('footer_col1_title'));
        Setting::set('footer_col2_title', $request->input('footer_col2_title'));
        Setting::set('footer_col3_title', $request->input('footer_col3_title'));
        Setting::set('footer_social_twitter', $request->input('footer_social_twitter') ?? '#');
        Setting::set('footer_social_youtube', $request->input('footer_social_youtube') ?? '#');
        Setting::set('footer_social_facebook', $request->input('footer_social_facebook') ?? '#');
        Setting::set('footer_social_instagram', $request->input('footer_social_instagram') ?? '#');
        Setting::set('footer_disclaimer', $request->input('footer_disclaimer') ?? '');

        // Header settings saving
        Setting::set('header_link_1_text', $request->input('header_link_1_text') ?? '');
        Setting::set('header_link_1_url', $request->input('header_link_1_url') ?? '');
        Setting::set('header_link_1_onclick', $request->input('header_link_1_onclick') ?? '');
        Setting::set('header_link_2_text', $request->input('header_link_2_text') ?? '');
        Setting::set('header_link_2_url', $request->input('header_link_2_url') ?? '');
        Setting::set('header_link_2_onclick', $request->input('header_link_2_onclick') ?? '');
        Setting::set('header_link_3_text', $request->input('header_link_3_text') ?? '');
        Setting::set('header_link_3_url', $request->input('header_link_3_url') ?? '');
        Setting::set('header_link_3_onclick', $request->input('header_link_3_onclick') ?? '');
        Setting::set('header_link_4_text', $request->input('header_link_4_text') ?? '');
        Setting::set('header_link_4_url', $request->input('header_link_4_url') ?? '');
        Setting::set('header_link_4_onclick', $request->input('header_link_4_onclick') ?? '');
        Setting::set('header_link_5_text', $request->input('header_link_5_text') ?? '');
        Setting::set('header_link_5_url', $request->input('header_link_5_url') ?? '');
        Setting::set('header_link_5_onclick', $request->input('header_link_5_onclick') ?? '');
        Setting::set('header_btn_login_text', $request->input('header_btn_login_text') ?? 'ENTRAR');
        Setting::set('header_btn_register_text', $request->input('header_btn_register_text') ?? 'CADASTRAR');
        Setting::set('header_pix_badge_text', $request->input('header_pix_badge_text') ?? 'PIX');

        // Mail (SMTP) settings saving
        Setting::set('mail_host', $request->input('mail_host') ?? '');
        Setting::set('mail_port', $request->input('mail_port') ?? '587');
        Setting::set('mail_username', $request->input('mail_username') ?? '');
        Setting::set('mail_password', $request->input('mail_password') ?? '');
        Setting::set('mail_encryption', $request->input('mail_encryption') ?? 'tls');
        Setting::set('mail_from_address', $request->input('mail_from_address') ?? '');
        Setting::set('mail_from_name', $request->input('mail_from_name') ?? 'Vibra');

        // Dynamic footer link saving
        for ($col = 1; $col <= 3; $col++) {
            for ($link = 1; $link <= 5; $link++) {
                Setting::set("footer_col{$col}_link_{$link}_text", $request->input("footer_col{$col}_link_{$link}_text") ?? '');
                Setting::set("footer_col{$col}_link_{$link}_url", $request->input("footer_col{$col}_link_{$link}_url") ?? '#');
            }
        }

        // Handle custom bottom navigation icon uploads and removals
        for ($i = 1; $i <= 5; $i++) {
            if ($request->input("remove_bottom_nav_btn_{$i}_icon") == '1') {
                $oldPath = Setting::get("bottom_nav_btn_{$i}_icon");
                if ($oldPath && file_exists(public_path($oldPath))) {
                    @unlink(public_path($oldPath));
                }
                Setting::set("bottom_nav_btn_{$i}_icon", '');
            } elseif ($request->hasFile("bottom_nav_btn_{$i}_icon")) {
                $iconFile = $request->file("bottom_nav_btn_{$i}_icon");
                $iconName = "bottom_nav_btn_{$i}_" . time() . '.' . $iconFile->getClientOriginalExtension();
                // Ensure the directory exists
                if (!file_exists(public_path('uploads'))) {
                    mkdir(public_path('uploads'), 0755, true);
                }
                $iconFile->move(public_path('uploads'), $iconName);
                
                // Delete old icon if it exists to clean up disk space
                $oldPath = Setting::get("bottom_nav_btn_{$i}_icon");
                if ($oldPath && file_exists(public_path($oldPath))) {
                    @unlink(public_path($oldPath));
                }
                
                Setting::set("bottom_nav_btn_{$i}_icon", 'uploads/' . $iconName);
            }
        }

        // Handle Site Logo Upload
        if ($request->hasFile('site_logo')) {
            $logo = $request->file('site_logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            // Move to public/uploads
            $logo->move(public_path('uploads'), $logoName);
            Setting::set('site_logo', 'uploads/' . $logoName);
        }

        // Handle Site Favicon Upload
        if ($request->hasFile('site_favicon')) {
            $favicon = $request->file('site_favicon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            // Move to public/uploads
            $favicon->move(public_path('uploads'), $faviconName);
            Setting::set('site_favicon', 'uploads/' . $faviconName);
        }

        // Handle Deposit Promo Image Upload
        if ($request->hasFile('deposit_promo_image')) {
            $promoImg = $request->file('deposit_promo_image');
            $promoImgName = 'deposit_promo_' . time() . '.' . $promoImg->getClientOriginalExtension();
            // Move to public/uploads
            $promoImg->move(public_path('uploads'), $promoImgName);
            Setting::set('deposit_promo_image', 'uploads/' . $promoImgName);
        }

        return back()->with('success', 'Configurações atualizadas com sucesso!');
    }

    /**
     * Send a test e-mail using the currently saved SMTP settings.
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        if (!Setting::get('mail_host')) {
            return back()->withErrors(['test_email' => 'Preencha e salve os dados do servidor SMTP antes de enviar um teste.']);
        }

        try {
            \App\Services\MailConfigService::apply();
            \Illuminate\Support\Facades\Mail::to($request->input('test_email'))
                ->send(new \App\Mail\PasswordResetCodeMail('123456', 'Teste'));
        } catch (\Exception $e) {
            return back()->withErrors(['test_email' => 'Falha ao enviar e-mail de teste: ' . $e->getMessage()]);
        }

        return back()->with('success', 'E-mail de teste enviado com sucesso para ' . $request->input('test_email') . '!');
    }

    /**
     * Show system personalization page (Banners).
     */
    public function personalization()
    {
        $banners = \App\Models\Banner::orderBy('order', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.personalization', compact('banners'));
    }

    /**
     * Store new banner.
     */
    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|string|in:home_carousel,login_modal,register_modal,login_modal_mobile,register_modal_mobile',
            'image' => 'required|image|max:3072',
            'link_url' => 'nullable|string|max:500',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'banner_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Move to public/uploads/banners
            $file->move(public_path('uploads/banners'), $fileName);
            $imagePath = 'uploads/banners/' . $fileName;

            // If it is login_modal, register_modal, login_modal_mobile or register_modal_mobile, deactivate previous ones of the same type
            if (in_array($request->type, ['login_modal', 'register_modal', 'login_modal_mobile', 'register_modal_mobile'])) {
                \App\Models\Banner::where('type', $request->type)->update(['is_active' => false]);
            }

            \App\Models\Banner::create([
                'title' => $request->input('title') ?? 'Banner ' . ucfirst($request->type),
                'type' => $request->type,
                'image_path' => $imagePath,
                'link_url' => $request->input('link_url'),
                'order' => $request->input('order', 0) ?? 0,
                'is_active' => true,
            ]);

            return back()->with('success', 'Banner cadastrado com sucesso!');
        }

        return back()->withErrors(['image' => 'Falha ao enviar a imagem.']);
    }

    /**
     * Update banner.
     */
    public function updateBanner(Request $request, $id)
    {
        $banner = \App\Models\Banner::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:home_carousel,login_modal,register_modal,login_modal_mobile,register_modal_mobile',
            'link_url' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $banner->title = $request->input('title');
        $banner->type = $request->input('type');
        $banner->link_url = $request->input('link_url');
        $banner->order = $request->input('order', 0);
        $banner->is_active = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Remove old file
            $oldPath = public_path($banner->image_path);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }

            $image = $request->file('image');
            $imageName = 'banner_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/banners'), $imageName);
            $banner->image_path = 'uploads/banners/' . $imageName;
        }

        $banner->save();

        return back()->with('success', 'Banner atualizado com sucesso!');
    }

    /**
     * Delete banner.
     */
    public function deleteBanner($id)
    {
        $banner = \App\Models\Banner::findOrFail($id);
        
        // Remove file from storage
        $filePath = public_path($banner->image_path);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $banner->delete();

        return back()->with('success', 'Banner removido com sucesso!');
    }

    /**
     * Update custom site colors.
     */
    public function updateColors(Request $request)
    {
        $colorFields = [
            'color_bg_color', 'color_header_bg', 'color_sidebar_bg', 'color_card_bg', 'color_card_hover',
            'color_border_color', 'color_neon_green', 'color_gold', 'color_red',
            'color_text_primary', 'color_text_secondary', 'color_text_muted',
            'color_btn_entrar_bg', 'color_btn_entrar_border', 'color_btn_entrar_text',
            'color_btn_cadastrar_bg_start', 'color_btn_cadastrar_bg_end', 'color_btn_cadastrar_text',
            'color_modal_bg', 'color_modal_border', 'color_modal_input_bg', 'color_modal_input_border',
            'color_modal_input_focus', 'color_btn_auth_submit_bg_start', 'color_btn_auth_submit_bg_end',
            'color_btn_auth_submit_text',
            'color_sidebar_btn_bg_start', 'color_sidebar_btn_bg_end', 'color_sidebar_btn_border',
            'color_sidebar_btn_text', 'sidebar_btn_show_v',
            'color_bottom_nav_btn_bg_start', 'color_bottom_nav_btn_bg_end', 'color_bottom_nav_btn_border',
            'color_bottom_nav_btn_text', 'bottom_nav_btn_show_v',
            'color_sidebar_menu_item_bg', 'color_sidebar_menu_item_text', 'color_sidebar_menu_item_icon',
            'color_header_deposit_btn_bg', 'color_header_deposit_btn_text',
            'color_header_balance_bg', 'color_header_balance_text',
            'color_header_profile_menu_bg', 'color_header_profile_menu_item_hover_bg',
            'color_header_profile_menu_text', 'color_bottom_nav_full_bg', 'color_banner_neon_hover',
            'color_header_profile_btn_bg', 'color_header_notif_btn_bg',
            'color_see_all_bg', 'color_see_all_border', 'color_see_all_text',
            'support_banner_bg_color', 'support_banner_btn_bg', 'support_banner_btn_text',
            'support_banner_title', 'support_banner_subtitle', 'support_banner_badge_text',
            'support_banner_link_url'
        ];

        $rules = [];
        foreach ($colorFields as $field) {
            if (strpos($field, 'color_') === 0 || strpos($field, 'support_banner_') === 0 && strpos($field, '_color') !== false || strpos($field, '_bg') !== false || strpos($field, '_text') !== false && strpos($field, '_title') === false && strpos($field, '_subtitle') === false && strpos($field, '_badge_text') === false) {
                $rules[$field] = ['nullable', 'string', 'regex:/^#([a-fA-F0-9]{3,8})$/'];
            } else {
                $rules[$field] = ['nullable', 'string', 'max:500'];
            }
        }
        
        // Image upload for support banner
        $rules['support_banner_image'] = 'nullable|image|max:2048';

        $request->validate($rules);

        if ($request->hasFile('support_banner_image')) {
            $image = $request->file('support_banner_image');
            $imageName = 'support_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/support'), $imageName);
            Setting::set('support_banner_image', 'uploads/support/' . $imageName);
        }

        foreach ($colorFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        return back()->with('success', 'Esquema de cores do site atualizado com sucesso!');
    }

    /**
     * Reset site colors to defaults.
     */
    public function resetColors()
    {
        $colorFields = [
            'color_bg_color', 'color_header_bg', 'color_sidebar_bg', 'color_card_bg', 'color_card_hover',
            'color_border_color', 'color_neon_green', 'color_gold', 'color_red',
            'color_text_primary', 'color_text_secondary', 'color_text_muted',
            'color_btn_entrar_bg', 'color_btn_entrar_border', 'color_btn_entrar_text',
            'color_btn_cadastrar_bg_start', 'color_btn_cadastrar_bg_end', 'color_btn_cadastrar_text',
            'color_modal_bg', 'color_modal_border', 'color_modal_input_bg', 'color_modal_input_border',
            'color_modal_input_focus', 'color_btn_auth_submit_bg_start', 'color_btn_auth_submit_bg_end',
            'color_btn_auth_submit_text',
            'color_sidebar_btn_bg_start', 'color_sidebar_btn_bg_end', 'color_sidebar_btn_border',
            'color_sidebar_btn_text', 'sidebar_btn_show_v',
            'color_bottom_nav_btn_bg_start', 'color_bottom_nav_btn_bg_end', 'color_bottom_nav_btn_border',
            'color_bottom_nav_btn_text', 'bottom_nav_btn_show_v'
        ];

        foreach ($colorFields as $field) {
            Setting::where('key', $field)->delete();
        }

        return back()->with('success', 'Cores padrão do site restauradas com sucesso!');
    }

    /**
     * Show system categories page.
     */
    public function categories()
    {
        $categories = GameCategory::orderBy('order')->get();
        return view('admin.categories', compact('categories'));
    }

    /**
     * Store a new category/game button.
     */
    public function storeCategoryButton(Request $request)
    {
        $validGroups = GameCategory::pluck('slug')->toArray();
        $request->validate([
            'group' => 'required|in:' . implode(',', $validGroups),
            'title' => 'required|string|max:255',
            'action_type' => 'required|in:link,scroll,alert,play,none',
            'action_value' => 'nullable|string|max:1000',
            'icon_class' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge_value' => 'nullable|string|max:255',
            'extra_info' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'nullable',
            'order' => 'required|integer',
        ]);

        $slug = \Illuminate\Support\Str::slug($request->title) . '-' . substr(uniqid(), -4);

        $btn = new CategoryButton();
        $btn->group = $request->input('group');
        $btn->title = $request->input('title');
        $btn->slug = $slug;
        $btn->action_type = $request->input('action_type');
        $btn->action_value = $request->input('action_value') ?? '';
        $btn->icon_class = $request->input('icon_class');
        $btn->subtitle = $request->input('subtitle');
        $btn->badge_value = $request->input('badge_value');
        $btn->extra_info = $request->input('extra_info');
        $btn->is_active = $request->has('is_active');
        $btn->order = intval($request->input('order'));

        // Handle Custom Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'cat_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            // Move to public/uploads/categories
            $image->move(public_path('uploads/categories'), $imageName);
            $btn->image_path = 'uploads/categories/' . $imageName;
        }

        $btn->save();

        return back()->with('success', "Item '{$btn->title}' cadastrado com sucesso!");
    }

    /**
     * Update category button.
     */
    public function updateCategoryButton(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'action_type' => 'required|in:link,scroll,alert,play,none',
            'action_value' => 'nullable|string|max:1000',
            'icon_class' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge_value' => 'nullable|string|max:255',
            'extra_info' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'nullable',
            'order' => 'required|integer',
        ]);

        $btn = CategoryButton::findOrFail($id);
        $btn->title = $request->input('title');
        $btn->action_type = $request->input('action_type');
        $btn->action_value = $request->input('action_value') ?? '';
        $btn->icon_class = $request->input('icon_class');
        $btn->subtitle = $request->input('subtitle');
        $btn->badge_value = $request->input('badge_value');
        $btn->extra_info = $request->input('extra_info');
        $btn->is_active = $request->has('is_active');
        $btn->order = intval($request->input('order'));

        // Handle Custom Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'cat_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            // Move to public/uploads/categories
            $image->move(public_path('uploads/categories'), $imageName);
            $btn->image_path = 'uploads/categories/' . $imageName;
        }

        $btn->save();

        return back()->with('success', "Item '{$btn->title}' atualizado com sucesso!");
    }

    /**
     * Delete category/game button.
     */
    public function deleteCategoryButton($id)
    {
        $btn = CategoryButton::findOrFail($id);
        
        // Remove file if exists
        if ($btn->image_path) {
            $filePath = public_path($btn->image_path);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $btn->delete();

        return back()->with('success', "Item '{$btn->title}' removido com sucesso!");
    }

    /**
     * Reset category buttons to defaults.
     */
    public function resetCategories()
    {
        $seeder = new \Database\Seeders\CategoryButtonSeeder();
        $seeder->run();

        return back()->with('success', 'Botões e categorias restaurados para os padrões originais!');
    }

    /**
     * Show categories management list page.
     */
    public function categoriesManage()
    {
        $categories = GameCategory::orderBy('order')->get();
        return view('admin.categories_manage', compact('categories'));
    }

    /**
     * Store new home category.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'layout_type' => 'required|in:payouts,recommended,weekly,live,sidebar,sports',
            'icon_class' => 'nullable|string',
            'order' => 'required|integer',
            'is_active' => 'nullable',
            'show_ver_todos' => 'nullable',
        ]);

        $slug = \Illuminate\Support\Str::slug($request->title);
        $count = GameCategory::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $category = new GameCategory();
        $category->title = $request->title;
        $category->slug = $slug;
        $category->layout_type = $request->layout_type;
        $category->icon_class = $request->icon_class;
        $category->order = intval($request->order);
        $category->is_active = $request->has('is_active');
        $category->show_ver_todos = $request->has('show_ver_todos');
        $category->save();

        return redirect()->route('admin.categories_manage')->with('success', "Categoria '{$category->title}' cadastrada com sucesso!");
    }

    /**
     * Update existing home category.
     */
    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'layout_type' => 'required|in:payouts,recommended,weekly,live,sidebar,sports',
            'icon_class' => 'nullable|string',
            'order' => 'required|integer',
            'is_active' => 'nullable',
            'show_ver_todos' => 'nullable',
        ]);

        $category = GameCategory::findOrFail($id);
        $oldSlug = $category->slug;
        $protected = ['sidebar', 'sports'];

        if (!in_array($oldSlug, $protected) && $category->title !== $request->title) {
            $slug = \Illuminate\Support\Str::slug($request->title);
            $count = GameCategory::where('slug', 'like', $slug . '%')->where('id', '!=', $id)->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
            $category->slug = $slug;
            CategoryButton::where('group', $oldSlug)->update(['group' => $slug]);
        }

        $category->title = $request->title;
        if (!in_array($oldSlug, $protected)) {
            $category->layout_type = $request->layout_type;
        }
        $category->icon_class = $request->icon_class;
        $category->order = intval($request->order);
        $category->is_active = $request->has('is_active');
        $category->show_ver_todos = $request->has('show_ver_todos');
        $category->save();

        return redirect()->route('admin.categories_manage')->with('success', "Categoria '{$category->title}' atualizada com sucesso!");
    }

    public function bulkReorderCategories(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|exists:game_categories,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->order as $index => $id) {
                    GameCategory::where('id', $id)->update(['order' => $index + 1]);
                }
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao salvar nova ordem: ' . $e->getMessage()]);
        }
    }

    public function reorderCategories(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:game_categories,id',
            'direction' => 'required|in:up,down',
        ]);

        $category = GameCategory::findOrFail($request->id);
        $currentOrder = $category->order;

        if ($request->direction === 'up') {
            $swapCategory = GameCategory::where('order', '<', $currentOrder)
                ->orderBy('order', 'desc')
                ->first();
        } else {
            $swapCategory = GameCategory::where('order', '>', $currentOrder)
                ->orderBy('order', 'asc')
                ->first();
        }

        if ($swapCategory) {
            $category->order = $swapCategory->order;
            $swapCategory->order = $currentOrder;

            $category->save();
            $swapCategory->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Não é possível mover nesta direção.']);
    }

    /**
     * Delete a category.
     */
    public function deleteCategory($id)
    {
        $category = GameCategory::findOrFail($id);
        $protected = ['sidebar', 'sports'];
        if (in_array($category->slug, $protected)) {
            return back()->withErrors(['error' => "As categorias da barra lateral ('sidebar') e esportes ('sports') são essenciais para o funcionamento do site e não podem ser excluídas."]);
        }

        // Delete associated category buttons
        CategoryButton::where('group', $category->slug)->delete();
        $category->delete();

        return redirect()->route('admin.categories_manage')->with('success', "Categoria '{$category->title}' e todos os seus jogos foram excluídos com sucesso!");
    }

    /**
     * New Games Management List.
     */
    public function games(Request $request)
    {
        $query = CategoryButton::where('action_type', 'play');

        // Filter by name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'LIKE', "%{$search}%");
        }

        // Filter by provider
        if ($request->filled('provider')) {
            $provider = $request->input('provider');
            $query->where('subtitle', 'LIKE', "%{$provider}%");
        }

        // Filter by distribution
        if ($request->filled('distribution')) {
            $distribution = $request->input('distribution');
            $query->where('distribution', $distribution);
        }

        // Filter by category
        if ($request->filled('category')) {
            $category = $request->input('category');
            $query->where('group', $category);
        }

        $games = $query->orderBy('order', 'asc')->paginate(20)->withQueryString();
        $categories = GameCategory::where('is_active', true)->orderBy('order')->get();
        
        // Get unique providers for the filter dropdown
        $providers = CategoryButton::where('action_type', 'play')
            ->whereNotNull('subtitle')
            ->where('subtitle', '!=', '')
            ->distinct()
            ->pluck('subtitle');

        return view('admin.games', compact('games', 'categories', 'providers'));
    }

    /**
     * Bulk actions for games.
     */
    public function bulkActionGames(Request $request)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');

        if (empty($ids)) {
            return back()->with('error', 'Nenhum jogo selecionado.');
        }

        if ($action === 'delete') {
            $games = CategoryButton::whereIn('id', $ids)->get();
            foreach ($games as $game) {
                if ($game->image_path && file_exists(public_path($game->image_path))) {
                    @unlink(public_path($game->image_path));
                }
                $game->delete();
            }
            return back()->with('success', count($ids) . ' jogos excluídos com sucesso.');
        }

        if ($action === 'activate') {
            CategoryButton::whereIn('id', $ids)->update(['is_active' => true]);
            return back()->with('success', count($ids) . ' jogos ativados com sucesso.');
        }

        if ($action === 'deactivate') {
            CategoryButton::whereIn('id', $ids)->update(['is_active' => false]);
            return back()->with('success', count($ids) . ' jogos desativados com sucesso.');
        }

        return back()->with('error', 'Ação inválida.');
    }

    /**
     * Store new game.
     */
    public function storeGame(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'action_value' => 'required|string|max:255', // Código do jogo
            'subtitle' => 'nullable|string|max:255', // Provedor
            'distribution' => 'nullable|string|max:255', // API
            'image' => 'nullable|image|max:2048',
            'import_image_url' => 'nullable|string',
            'order' => 'required|integer',
            'is_active' => 'nullable',
            'is_original' => 'nullable',
        ]);

        $slug = \Illuminate\Support\Str::slug($request->title) . '-' . substr(uniqid(), -4);

        $game = new CategoryButton();
        $game->title = $request->title;
        $game->group = $request->group;
        $game->action_type = 'play'; // Padrão para jogos
        $game->action_value = $request->action_value;
        $game->subtitle = $request->subtitle;
        $game->distribution = $request->distribution;
        $game->is_original = $request->has('is_original');
        $game->slug = $slug;
        $game->order = intval($request->order);
        $game->is_active = $request->has('is_active');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'game_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/categories'), $imageName);
            $game->image_path = 'uploads/categories/' . $imageName;
        } elseif ($request->filled('import_image_url')) {
            $url = $request->input('import_image_url');
            if (str_starts_with($url, 'http')) {
                if ($request->distribution === 'maxapigames') {
                    // Imagens da MAX API GAMES possuem proteção anti-hotlink: é necessário baixar localmente
                    $game->image_path = $this->downloadExternalGameImage($url) ?? $url;
                } else {
                    $game->image_path = $url;
                }
            }
        }

        $game->save();

        return redirect()->route('admin.games')->with('success', "Jogo '{$game->title}' cadastrado com sucesso!");
    }

    /**
     * Download an external game thumbnail to the local server (used for providers
     * with anti-hotlink protection, e.g. MAX API GAMES).
     */
    private function downloadExternalGameImage($url)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(15)->get($url);

            if (!$response->successful()) {
                return null;
            }

            $extension = pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION) ?: 'png';
            $fileName = 'game_' . time() . '_' . uniqid() . '.' . $extension;
            $destination = public_path('uploads/categories');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            file_put_contents($destination . '/' . $fileName, $response->body());

            return 'uploads/categories/' . $fileName;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Falha ao baixar imagem do jogo: ' . $e->getMessage(), ['url' => $url]);
            return null;
        }
    }

    /**
     * Import multiple games in bulk from API.
     */
    public function bulkImportGames(Request $request)
    {
        $request->validate([
            'games' => 'required|array',
            'games.*.title' => 'required|string|max:255',
            'games.*.action_value' => 'required|string|max:255',
            'games.*.subtitle' => 'nullable|string|max:255',
            'games.*.image_url' => 'nullable|string',
            'group' => 'required|string|max:255', // Categoria selecionada
            'distribution' => 'nullable|string|max:255',
        ]);

        $group = $request->input('group');
        $gamesData = $request->input('games');
        $distribution = $request->input('distribution', 'playfiver');
        $importedCount = 0;

        foreach ($gamesData as $g) {
            // Check if game already exists with same action_value (code) and provider
            $exists = CategoryButton::where('action_value', $g['action_value'])
                ->where('subtitle', $g['subtitle'])
                ->exists();

            if ($exists) {
                continue; // Skip already imported games
            }

            $slug = \Illuminate\Support\Str::slug($g['title']) . '-' . substr(uniqid(), -4);

            $game = new CategoryButton();
            $game->title = $g['title'];
            $game->group = $group;
            $game->action_type = 'play';
            $game->action_value = $g['action_value'];
            $game->subtitle = $g['subtitle'];
            $game->distribution = $distribution;
            $game->is_original = true;
            $game->slug = $slug;
            $game->order = 10;
            $game->is_active = true;

            if (!empty($g['image_url']) && str_starts_with($g['image_url'], 'http')) {
                if ($distribution === 'maxapigames') {
                    // Imagens da MAX API GAMES possuem proteção anti-hotlink: é necessário baixar localmente
                    $game->image_path = $this->downloadExternalGameImage($g['image_url']) ?? $g['image_url'];
                } else {
                    $game->image_path = $g['image_url'];
                }
            }

            $game->save();
            $importedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "{$importedCount} jogos importados com sucesso para a categoria '{$group}'!"
        ]);
    }

    /**
     * Update existing game.
     */
    public function updateGame(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'action_value' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'distribution' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'order' => 'required|integer',
            'is_active' => 'nullable',
            'is_original' => 'nullable',
        ]);

        $game = CategoryButton::findOrFail($id);
        $game->title = $request->title;
        $game->group = $request->group;
        $game->action_value = $request->action_value;
        $game->subtitle = $request->subtitle;
        $game->distribution = $request->distribution;
        $game->is_original = $request->has('is_original');
        $game->order = intval($request->order);
        $game->is_active = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($game->image_path && file_exists(public_path($game->image_path))) {
                @unlink(public_path($game->image_path));
            }

            $image = $request->file('image');
            $imageName = 'game_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/categories'), $imageName);
            $game->image_path = 'uploads/categories/' . $imageName;
        }

        $game->save();

        return redirect()->route('admin.games')->with('success', "Jogo '{$game->title}' atualizado com sucesso!");
    }

    /**
     * Delete game.
     */
    public function deleteGame($id)
    {
        $game = CategoryButton::findOrFail($id);
        
        if ($game->image_path && file_exists(public_path($game->image_path))) {
            @unlink(public_path($game->image_path));
        }

        $game->delete();

        return redirect()->route('admin.games')->with('success', "Jogo removido com sucesso!");
    }

    /**
     * Show detailed metrics page.
     */
    public function metrics(Request $request)
    {
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        if ($startDateInput && $endDateInput) {
            try {
                $start = \Carbon\Carbon::parse($startDateInput, 'America/Sao_Paulo')->startOfDay()->setTimezone('UTC');
                $end = \Carbon\Carbon::parse($endDateInput, 'America/Sao_Paulo')->endOfDay()->setTimezone('UTC');
            } catch (\Exception $e) {
                $start = now('America/Sao_Paulo')->subDays(30)->startOfDay()->setTimezone('UTC');
                $end = now('America/Sao_Paulo')->endOfDay()->setTimezone('UTC');
            }
        } else {
            $start = now('America/Sao_Paulo')->subDays(30)->startOfDay()->setTimezone('UTC');
            $end = now('America/Sao_Paulo')->endOfDay()->setTimezone('UTC');
        }

        $startDateStr = \Carbon\Carbon::parse($start)->setTimezone('America/Sao_Paulo')->format('Y-m-d');
        $endDateStr = \Carbon\Carbon::parse($end)->setTimezone('America/Sao_Paulo')->format('Y-m-d');

        // --- Core Betting Metrics ---
        $totalBetsCount = Bet::whereBetween('created_at', [$start, $end])->count();
        $totalBetAmount = Bet::whereBetween('created_at', [$start, $end])->sum('bet_amount');
        $totalPayout = Bet::whereBetween('created_at', [$start, $end])->sum('payout');
        $ggr = $totalBetAmount - $totalPayout;
        $rtp = $totalBetAmount > 0 ? ($totalPayout / $totalBetAmount) * 100 : 0.00;

        // --- Core Financial Metrics ---
        $totalDepositsSum = Deposit::where('status', 'approved')->whereBetween('created_at', [$start, $end])->sum('amount');
        $totalDepositsCount = Deposit::where('status', 'approved')->whereBetween('created_at', [$start, $end])->count();
        $totalWithdrawalsSum = Withdrawal::where('status', 'approved')->whereBetween('created_at', [$start, $end])->sum('amount');
        $totalWithdrawalsCount = Withdrawal::where('status', 'approved')->whereBetween('created_at', [$start, $end])->count();
        $netFinancialProfit = $totalDepositsSum - $totalWithdrawalsSum;

        // --- Average Tickets ---
        $avgDepositTicket = $totalDepositsCount > 0 ? ($totalDepositsSum / $totalDepositsCount) : 0.00;
        $avgBetTicket = $totalBetsCount > 0 ? ($totalBetAmount / $totalBetsCount) : 0.00;

        // --- Most Popular Game ---
        $popularGameResult = Bet::select('game', DB::raw('COUNT(*) as total_bets'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('game')
            ->orderBy('total_bets', 'desc')
            ->first();
        $popularGame = $popularGameResult ? $popularGameResult->game : 'Nenhum';
        $popularGameCount = $popularGameResult ? $popularGameResult->total_bets : 0;

        // --- Game Performance ---
        $gameStats = Bet::select(
                'game',
                DB::raw('COUNT(*) as total_bets'),
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(payout) as total_payout'),
                DB::raw('SUM(bet_amount) - SUM(payout) as ggr')
            )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('game')
            ->orderBy('total_bet_amount', 'desc')
            ->get();

        // --- Top Players (High Rollers) ---
        $topPlayers = Bet::select(
                'user_id',
                DB::raw('COUNT(*) as total_bets'),
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(payout) as total_payout'),
                DB::raw('SUM(bet_amount) - SUM(payout) as ggr')
            )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('user_id')
            ->orderBy('total_bet_amount', 'desc')
            ->with('user')
            ->limit(10)
            ->get();

        // --- Daily Evolution (Deposits vs. Withdrawals) using Brazil Timezone conversion ---
        $dailyDeposits = Deposit::select(
                DB::raw("DATE(CONVERT_TZ(created_at, '+00:00', '-03:00')) as date"),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'approved')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        $dailyWithdrawals = Withdrawal::select(
                DB::raw("DATE(CONVERT_TZ(created_at, '+00:00', '-03:00')) as date"),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'approved')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        // --- Daily GGR Evolution using Brazil Timezone conversion ---
        $dailyGGR = Bet::select(
                DB::raw("DATE(CONVERT_TZ(created_at, '+00:00', '-03:00')) as date"),
                DB::raw('SUM(bet_amount) - SUM(payout) as total')
            )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        // Merging dates for Chart (using local America/Sao_Paulo timezone dates)
        $dates = [];
        $curr = \Carbon\Carbon::parse($start)->setTimezone('America/Sao_Paulo');
        $endLocal = \Carbon\Carbon::parse($end)->setTimezone('America/Sao_Paulo');
        while ($curr->lte($endLocal)) {
            $dates[] = $curr->format('Y-m-d');
            $curr->addDay();
        }

        $chartLabels = [];
        $chartDeposits = [];
        $chartWithdrawals = [];
        $chartGGR = [];

        foreach ($dates as $date) {
            $chartLabels[] = \Carbon\Carbon::parse($date)->format('d/m');
            $chartDeposits[] = floatval($dailyDeposits[$date] ?? 0.00);
            $chartWithdrawals[] = floatval($dailyWithdrawals[$date] ?? 0.00);
            $chartGGR[] = floatval($dailyGGR[$date] ?? 0.00);
        }

        return view('admin.metrics', compact(
            'startDateStr', 'endDateStr',
            'totalBetsCount', 'totalBetAmount', 'totalPayout', 'ggr', 'rtp',
            'totalDepositsSum', 'totalDepositsCount', 'totalWithdrawalsSum', 'totalWithdrawalsCount', 'netFinancialProfit',
            'avgDepositTicket', 'avgBetTicket', 'popularGame', 'popularGameCount',
            'gameStats', 'topPlayers',
            'chartLabels', 'chartDeposits', 'chartWithdrawals', 'chartGGR'
        ));
    }

    /**
     * Get PlayFiver Providers.
     */
    public function getPlayFiverProviders()
    {
        $service = new \App\Helpers\PlayFiverService();
        return response()->json($service->getProviders());
    }

    /**
     * Get PlayFiver Games by Provider.
     */
    public function getPlayFiverGames(Request $request)
    {
        $providerId = $request->input('provider_id');
        $service = new \App\Helpers\PlayFiverService();
        return response()->json($service->getGames($providerId));
    }

    /**
     * Get MAX API GAMES Providers.
     */
    public function getMaxApiGamesProviders()
    {
        $service = new \App\Helpers\MaxApiGamesService();
        $result = $service->getProviders();

        if (!isset($result['status']) || (int)$result['status'] !== 1) {
            return response()->json(['status' => 0, 'msg' => $result['msg'] ?? 'Erro ao buscar provedores']);
        }

        $providers = collect($result['providers'] ?? [])->map(function ($p) {
            return ['id' => $p['code'] ?? '', 'name' => $p['name'] ?? ($p['code'] ?? '')];
        });

        return response()->json(['status' => 1, 'data' => $providers]);
    }

    /**
     * Get MAX API GAMES Games by Provider.
     */
    public function getMaxApiGamesGames(Request $request)
    {
        $providerCode = $request->input('provider_id');
        $service = new \App\Helpers\MaxApiGamesService();
        $result = $service->getGames($providerCode);

        if (!isset($result['status']) || (int)$result['status'] !== 1) {
            return response()->json(['status' => 0, 'msg' => $result['msg'] ?? 'Erro ao buscar jogos']);
        }

        $games = collect($result['games'] ?? [])->map(function ($g) {
            return [
                'game_code' => $g['game_code'] ?? '',
                'game_name' => $g['game_name'] ?? '',
                'provider' => $g['provider_code'] ?? '',
                'banner' => $g['banner'] ?? null,
                'game_original' => false,
            ];
        });

        return response()->json(['status' => 1, 'data' => $games]);
    }

    /**
     * Search a game by name across the full catalog of the chosen API distribution
     * (used by the "Adicionar Jogo" modal to auto-fill fields instead of typing manually).
     */
    public function searchProviderGames(Request $request)
    {
        $distribution = $request->input('distribution');
        $query = trim((string)$request->input('q'));

        if (strlen($query) < 2 || !in_array($distribution, ['playfiver', 'maxapigames'])) {
            return response()->json(['status' => 1, 'data' => []]);
        }

        $cacheKey = "provider_catalog_{$distribution}";
        $catalog = \Illuminate\Support\Facades\Cache::remember($cacheKey, 300, function () use ($distribution) {
            if ($distribution === 'maxapigames') {
                $service = new \App\Helpers\MaxApiGamesService();
                $result = $service->getGames();
                return collect($result['games'] ?? [])->map(fn ($g) => [
                    'game_code' => $g['game_code'] ?? '',
                    'game_name' => $g['game_name'] ?? '',
                    'provider' => $g['provider_code'] ?? '',
                    'image' => $g['banner'] ?? null,
                    'game_original' => false,
                ]);
            }

            $service = new \App\Helpers\PlayFiverService();
            $result = $service->getGames();
            return collect($result['data'] ?? $result['games'] ?? [])->map(function ($g) {
                $provider = (isset($g['provider']) && is_array($g['provider'])) ? ($g['provider']['name'] ?? '') : ($g['provider'] ?? '');
                return [
                    'game_code' => $g['game_code'] ?? ($g['code'] ?? ''),
                    'game_name' => $g['game_name'] ?? ($g['name'] ?? ''),
                    'provider' => $provider,
                    'image' => $g['image_url'] ?? ($g['banner'] ?? ($g['image'] ?? ($g['icon'] ?? null))),
                    'game_original' => (bool)($g['game_original'] ?? false),
                ];
            });
        });

        $needle = mb_strtolower($query);
        $matches = $catalog->filter(function ($g) use ($needle) {
            return str_contains(mb_strtolower($g['game_name']), $needle);
        })->values()->take(15);

        return response()->json(['status' => 1, 'data' => $matches]);
    }

    /**
     * Show Tournaments Management page.
     */
    public function tournaments()
    {
        $tournaments = \App\Models\Tournament::orderBy('id', 'desc')->get();
        // Fetch all playable games in the system for selection
        $games = \App\Models\CategoryButton::where('action_type', 'play')
            ->orderBy('title')
            ->get()
            ->unique('action_value'); // unique by game code

        return view('admin.tournaments', compact('tournaments', 'games'));
    }

    /**
     * Store new Tournament.
     */
    public function storeTournament(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prize_pool' => 'required|numeric|min:0.01',
            'banner' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rules' => 'nullable|string',
            'games' => 'required|array',
            'games.*' => 'string',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $fileName = 'tournament_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path('uploads/banners'))) {
                mkdir(public_path('uploads/banners'), 0755, true);
            }
            $file->move(public_path('uploads/banners'), $fileName);
            $bannerPath = 'uploads/banners/' . $fileName;
        }

        \App\Models\Tournament::create([
            'title' => $request->title,
            'description' => $request->description,
            'prize_pool' => $request->prize_pool,
            'banner_path' => $bannerPath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'rules' => $request->rules,
            'games' => $request->games,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Torneio cadastrado com sucesso!');
    }

    /**
     * Update existing Tournament.
     */
    public function updateTournament(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prize_pool' => 'required|numeric|min:0.01',
            'banner' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rules' => 'nullable|string',
            'games' => 'required|array',
            'games.*' => 'string',
        ]);

        $tournament = \App\Models\Tournament::findOrFail($id);
        
        $bannerPath = $tournament->banner_path;
        if ($request->hasFile('banner')) {
            // Delete old banner
            if ($bannerPath && file_exists(public_path($bannerPath))) {
                @unlink(public_path($bannerPath));
            }
            
            $file = $request->file('banner');
            $fileName = 'tournament_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path('uploads/banners'))) {
                mkdir(public_path('uploads/banners'), 0755, true);
            }
            $file->move(public_path('uploads/banners'), $fileName);
            $bannerPath = 'uploads/banners/' . $fileName;
        }

        $tournament->update([
            'title' => $request->title,
            'description' => $request->description,
            'prize_pool' => $request->prize_pool,
            'banner_path' => $bannerPath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'rules' => $request->rules,
            'games' => $request->games,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Torneio atualizado com sucesso!');
    }

    /**
     * Delete Tournament.
     */
    public function deleteTournament($id)
    {
        $tournament = \App\Models\Tournament::findOrFail($id);
        
        if ($tournament->banner_path && file_exists(public_path($tournament->banner_path))) {
            @unlink(public_path($tournament->banner_path));
        }

        $tournament->delete();

        return back()->with('success', 'Torneio removido com sucesso!');
    }

    /**
     * Show Missions Management page.
     */
    public function missions()
    {
        $missions = \App\Models\Mission::orderBy('id', 'desc')->get();
        $games = \App\Models\CategoryButton::where('action_type', 'play')
            ->orderBy('title')
            ->get()
            ->unique('action_value'); // unique by game code

        return view('admin.missions', compact('missions', 'games'));
    }

    /**
     * Store new Mission.
     */
    public function storeMission(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:bet_count,bet_amount,deposit_amount',
            'game_slug' => 'nullable|string',
            'target_value' => 'required|numeric|min:0.01',
            'reward_amount' => 'required|numeric|min:0.01',
            'period' => 'required|string|in:daily,weekly,once',
        ]);

        \App\Models\Mission::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'game_slug' => $request->game_slug,
            'target_value' => $request->target_value,
            'reward_amount' => $request->reward_amount,
            'period' => $request->period,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Missão cadastrada com sucesso!');
    }

    /**
     * Update existing Mission.
     */
    public function updateMission(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:bet_count,bet_amount,deposit_amount',
            'game_slug' => 'nullable|string',
            'target_value' => 'required|numeric|min:0.01',
            'reward_amount' => 'required|numeric|min:0.01',
            'period' => 'required|string|in:daily,weekly,once',
        ]);

        $mission = \App\Models\Mission::findOrFail($id);

        $mission->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'game_slug' => $request->game_slug,
            'target_value' => $request->target_value,
            'reward_amount' => $request->reward_amount,
            'period' => $request->period,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Missão atualizada com sucesso!');
    }

    /**
     * Delete Mission.
     */
    public function deleteMission($id)
    {
        $mission = \App\Models\Mission::findOrFail($id);
        $mission->delete();

        return back()->with('success', 'Missão removida com sucesso!');
    }

    /**
     * Show affiliates tracking panel.
     */
    public function affiliates()
    {
        $referredUsers = User::whereNotNull('referred_by')
            ->with(['referrer'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        $totalReferrals = User::whereNotNull('referred_by')->count();
        
        $referredUserIds = User::whereNotNull('referred_by')->pluck('id');
        $totalDepositedByReferred = Deposit::whereIn('user_id', $referredUserIds)
            ->where('status', 'approved')
            ->sum('amount');
            
        $commissionPercent = floatval(Setting::get('affiliate_commission_percent', '10'));
        $totalCommissionPaid = 0;
        
        foreach (Deposit::whereIn('user_id', $referredUserIds)->where('status', 'approved')->get() as $dep) {
             $totalCommissionPaid += $dep->amount * ($commissionPercent / 100);
        }

        foreach ($referredUsers as $user) {
             $userDeposited = Deposit::where('user_id', $user->id)->where('status', 'approved')->sum('amount');
             $user->total_deposited = $userDeposited;
             $user->commission_generated = $userDeposited * ($commissionPercent / 100);
        }

        return view('admin.affiliates', compact(
            'referredUsers',
            'totalReferrals',
            'totalDepositedByReferred',
            'totalCommissionPaid',
            'commissionPercent'
        ));
    }

    /**
     * Show VIP Levels list and management.
     */
    public function vipLevels()
    {
        $vipLevels = VipLevel::orderBy('level_number', 'asc')->get();
        return view('admin.vip_levels', compact('vipLevels'));
    }

    /**
     * Store new VIP Level.
     */
    public function storeVipLevel(Request $request)
    {
        $request->validate([
            'level_number' => 'required|integer|unique:vip_levels,level_number',
            'name' => 'required|string|max:255',
            'required_points' => 'required|integer|min:0',
            'exchange_rate' => 'required|numeric|min:0.0001',
            'min_exchange' => 'required|integer|min:1',
            'cashback_percent' => 'required|numeric|min:0|max:100',
            'badge' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'benefits' => 'nullable|array',
        ]);

        $badgePath = null;
        if ($request->hasFile('badge')) {
            $file = $request->file('badge');
            $fileName = 'badge_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path('uploads/vip'))) {
                mkdir(public_path('uploads/vip'), 0755, true);
            }
            $file->move(public_path('uploads/vip'), $fileName);
            $badgePath = '/uploads/vip/' . $fileName;
        }

        VipLevel::create([
            'level_number' => $request->level_number,
            'name' => $request->name,
            'required_points' => $request->required_points,
            'exchange_rate' => $request->exchange_rate,
            'min_exchange' => $request->min_exchange,
            'cashback_percent' => $request->cashback_percent,
            'badge_path' => $badgePath,
            'benefits' => $request->input('benefits', []),
        ]);

        return back()->with('success', 'Nível VIP cadastrado com sucesso!');
    }

    /**
     * Update existing VIP Level.
     */
    public function updateVipLevel(Request $request, $id)
    {
        $vipLevel = VipLevel::findOrFail($id);

        $request->validate([
            'level_number' => 'required|integer|unique:vip_levels,level_number,' . $vipLevel->id,
            'name' => 'required|string|max:255',
            'required_points' => 'required|integer|min:0',
            'exchange_rate' => 'required|numeric|min:0.0001',
            'min_exchange' => 'required|integer|min:1',
            'cashback_percent' => 'required|numeric|min:0|max:100',
            'badge' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'benefits' => 'nullable|array',
        ]);

        $badgePath = $vipLevel->badge_path;
        if ($request->hasFile('badge')) {
            // Remove old file
            if ($badgePath && file_exists(public_path($badgePath))) {
                @unlink(public_path($badgePath));
            }

            $file = $request->file('badge');
            $fileName = 'badge_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path('uploads/vip'))) {
                mkdir(public_path('uploads/vip'), 0755, true);
            }
            $file->move(public_path('uploads/vip'), $fileName);
            $badgePath = '/uploads/vip/' . $fileName;
        }

        $vipLevel->update([
            'level_number' => $request->level_number,
            'name' => $request->name,
            'required_points' => $request->required_points,
            'exchange_rate' => $request->exchange_rate,
            'min_exchange' => $request->min_exchange,
            'cashback_percent' => $request->cashback_percent,
            'badge_path' => $badgePath,
            'benefits' => $request->input('benefits', []),
        ]);

        return back()->with('success', 'Nível VIP atualizado com sucesso!');
    }

    /**
     * Delete VIP Level.
     */
    public function deleteVipLevel($id)
    {
        $vipLevel = VipLevel::findOrFail($id);

        if ($vipLevel->badge_path && file_exists(public_path($vipLevel->badge_path))) {
            @unlink(public_path($vipLevel->badge_path));
        }

        $vipLevel->delete();

        return back()->with('success', 'Nível VIP removido com sucesso!');
    }
}


