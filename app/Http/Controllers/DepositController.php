<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
    /**
     * Create a pending deposit and generate mock PIX QR Code & copy/paste payload.
     */
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa estar logado para realizar um depósito.'
            ], 401);
        }

        $minDeposit = floatval(Setting::get('min_deposit', '5.00'));
        $maxDeposit = floatval(Setting::get('max_deposit', '10000.00'));

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:' . $minDeposit . '|max:' . $maxDeposit,
            'payment_method' => 'required|string|max:50',
        ], [
            'amount.min' => 'O valor mínimo de depósito é R$ ' . number_format($minDeposit, 2, ',', '.'),
            'amount.max' => 'O valor máximo de depósito é R$ ' . number_format($maxDeposit, 2, ',', '.'),
            'amount.required' => 'O valor é obrigatório.',
            'amount.numeric' => 'O valor informado deve ser um número válido.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $amount = floatval($request->input('amount'));
        $gateway = strtolower($request->input('payment_method'));
        $transactionId = 'DEP-' . strtoupper(bin2hex(random_bytes(5)));

        $couponId = null;
        if ($request->filled('coupon_id')) {
            $couponObj = \App\Models\Coupon::find($request->input('coupon_id'));
            if ($couponObj && $couponObj->isValidFor($amount)) {
                $couponId = $couponObj->id;
            }
        }

        // If the gateway is heartpay, let's call the HeartPay API
        if ($gateway === 'heartpay') {
            $apiKey = Setting::get('heartpay_api_key');
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'HeartPay não está configurada corretamente pelo administrador.'
                ], 500);
            }

            // We need the user's CPF. Since we added the cpf column, let's verify if the user has a CPF.
            $cpf = $user->cpf;
            if (empty($cpf)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você precisa ter um CPF cadastrado em sua conta para depositar via HeartPay.'
                ], 422);
            }

            try {
                // Create the pending deposit first
                $deposit = Deposit::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'status' => 'pending',
                    'payment_method' => 'pix_heartpay',
                    'transaction_id' => $transactionId,
                    'coupon_id' => $couponId,
                ]);

                // Call HeartPay charges API
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://app.heartpag.com/api/v1/client/charges', [
                    'value' => intval(round($amount * 100)), // cents
                    'correlationID' => 'deposit_id_' . $deposit->id,
                    'comment' => 'Deposito Fone777 #' . $deposit->id,
                    'customer' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'taxID' => preg_replace('/\D/', '', $cpf),
                    ],
                ]);

                if (!$response->successful()) {
                    $errData = $response->json();
                    throw new \Exception($errData['message'] ?? $errData['error'] ?? 'Erro na chamada da API HeartPay.');
                }

                $resData = $response->json();
                $dataNode = $resData['data'] ?? $resData;
                $brCode = $dataNode['brCode'] ?? ($resData['brCode'] ?? null);
                $qrCode = $dataNode['qrCodeImage'] ?? ($resData['qrCodeImage'] ?? null);

                if (empty($brCode)) {
                    throw new \Exception('Chave Pix não recebida do gateway.');
                }

                if (empty($qrCode)) {
                    $qrCode = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($brCode);
                }

                return response()->json([
                    'success' => true,
                    'deposit_id' => $deposit->id,
                    'transaction_id' => $transactionId,
                    'amount' => $amount,
                    'amount_formatted' => number_format($amount, 2, ',', '.'),
                    'pix_key' => $brCode,
                    'qr_code_url' => $qrCode
                ]);

            } catch (\Exception $e) {
                if (isset($deposit)) {
                    $deposit->delete();
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao processar depósito com HeartPay: ' . $e->getMessage()
                ], 500);
            }
        }

        try {
            // Save deposit to database
            $deposit = Deposit::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'status' => 'pending',
                'payment_method' => strtolower($gateway) === 'pix' ? 'pix' : 'pix_' . strtolower($gateway),
                'transaction_id' => $transactionId,
                'coupon_id' => $couponId,
            ]);

            // Generate mock PIX Payload
            $amountFormatted = sprintf("%.2f", $amount);
            // Payload PIX format mockup
            $merchantName = str_replace(' ', '', substr(Setting::get('site_name', 'Fone777'), 0, 25));
            $pixKey = "fone777.online-pixkey-placeholder-" . $transactionId;
            
            // Standard PIX EMV structure
            // 000201 (version)
            // 26 (merchant account info) -> 0014br.gov.bcb.pix -> 01(key length)key
            $merchantAccount = "0014br.gov.bcb.pix01" . strlen($pixKey) . $pixKey;
            $payload = "000201" 
                . "26" . strlen($merchantAccount) . $merchantAccount 
                . "52040000" // Merchant category code
                . "5303986" // Transaction currency (986 = BRL)
                . "54" . sprintf("%02d", strlen($amountFormatted)) . $amountFormatted // Transaction amount
                . "5802BR" // Country code
                . "59" . sprintf("%02d", strlen($merchantName)) . $merchantName // Merchant name
                . "6009Sao Paulo" // Merchant city
                . "62070503***" // Additional data field template
                . "6304"; // CRC16 placeholder (omitted or static for mockup)
            
            // Add a simple dynamic CRC
            $payload .= strtoupper(substr(md5($payload), 0, 4));

            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($payload);

            return response()->json([
                'success' => true,
                'deposit_id' => $deposit->id,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'amount_formatted' => number_format($amount, 2, ',', '.'),
                'pix_key' => $payload,
                'qr_code_url' => $qrCodeUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar o depósito: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle webhook notifications from HeartPay.
     */
    public function handleHeartpayWebhook(Request $request)
    {
        $rawBody = $request->getContent();
        $signature = $request->header('X-HeartPay-Signature');
        $timestamp = $request->header('X-HeartPay-Timestamp');
        
        $webhookToken = Setting::get('heartpay_webhook_token');
        
        \Illuminate\Support\Facades\Log::info('HeartPay Webhook Received', [
            'signature' => $signature,
            'timestamp' => $timestamp,
            'payload' => $rawBody
        ]);
        
        $signatureValid = false;
        if (empty($webhookToken)) {
            \Illuminate\Support\Facades\Log::warning('HeartPay Webhook: Webhook validation token is not configured in Admin Settings. Signature check skipped.');
            $signatureValid = true;
        } else {
            if (empty($signature) || empty($timestamp)) {
                \Illuminate\Support\Facades\Log::warning('HeartPay Webhook: Missing signature or timestamp headers', [
                    'signature' => $signature,
                    'timestamp' => $timestamp
                ]);
            } else {
                // Validar assinatura HMAC-SHA256: timestamp.payload
                $signedPayload = $timestamp . '.' . $rawBody;
                $expectedSignature = hash_hmac('sha256', $signedPayload, $webhookToken);
                
                if (hash_equals($expectedSignature, $signature)) {
                    $signatureValid = true;
                } else {
                    \Illuminate\Support\Facades\Log::warning('HeartPay Webhook: Invalid signature', [
                        'expected' => $expectedSignature,
                        'received' => $signature
                    ]);
                }
            }
        }
        
        if (!$signatureValid) {
            return response()->json(['error' => 'Assinatura inválida ou ausente'], 401);
        }
        
        // Verificar se o timestamp é muito antigo (mais de 5 minutos / 300 segundos) para evitar ataques de replay (apenas se validando assinatura)
        if (!empty($webhookToken) && !empty($timestamp)) {
            $timestampAge = time() - intval($timestamp);
            if ($timestampAge > 300) {
                \Illuminate\Support\Facades\Log::warning('HeartPay Webhook: Timestamp expired', [
                    'timestamp' => $timestamp,
                    'age' => $timestampAge
                ]);
                return response()->json(['error' => 'Notificação expirada'], 401);
            }
        }
        
        $payload = json_decode($rawBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            \Illuminate\Support\Facades\Log::error('HeartPay Webhook: Invalid JSON payload');
            return response()->json(['error' => 'JSON inválido'], 400);
        }
        
        $event = $payload['event'] ?? '';
        
        // Extract correlationID defensively checking multiple paths
        $correlationID = null;
        if (isset($payload['data']['data']['correlationID'])) {
            $correlationID = $payload['data']['data']['correlationID'];
        } elseif (isset($payload['data']['correlationID'])) {
            $correlationID = $payload['data']['correlationID'];
        } elseif (isset($payload['correlationID'])) {
            $correlationID = $payload['correlationID'];
        }
        
        \Illuminate\Support\Facades\Log::info('HeartPay Webhook Event details', [
            'event' => $event,
            'correlationID' => $correlationID
        ]);
        
        if (empty($correlationID)) {
            \Illuminate\Support\Facades\Log::warning('HeartPay Webhook: No correlationID found');
            return response()->json(['success' => true, 'message' => 'Nenhum correlationID encontrado.'], 200);
        }
        
        // Process event
        if ($event === 'PayInCompleted') {
            // Check if it is a deposit
            if (strpos($correlationID, 'deposit_id_') === 0) {
                $depositId = intval(substr($correlationID, 11));
                $deposit = Deposit::find($depositId);
                
                if ($deposit && $deposit->status === 'pending') {
                    \Illuminate\Support\Facades\DB::transaction(function() use ($deposit) {
                        $deposit->update(['status' => 'approved']);
                        $user = $deposit->user;
                        if ($user) {
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
                        }
                    });
                    
                    \Illuminate\Support\Facades\Log::info('HeartPay Webhook: Deposit approved successfully', [
                        'deposit_id' => $depositId,
                        'amount' => $deposit->amount,
                        'user_id' => $deposit->user_id
                    ]);
                    
                    return response()->json(['success' => true, 'message' => 'Depósito aprovado com sucesso!'], 200);
                } else {
                    \Illuminate\Support\Facades\Log::warning('HeartPay Webhook: Deposit not found or not pending', [
                        'deposit_id' => $depositId,
                        'status' => $deposit ? $deposit->status : 'not_found'
                    ]);
                }
            }
        } elseif (in_array($event, ['PAYOUT_COMPLETED', 'PayOutCompleted'])) {
            // Check if it is a withdrawal
            if (strpos($correlationID, 'withdrawal_id_') === 0) {
                $withdrawalId = intval(substr($correlationID, 14));
                $withdrawal = \App\Models\Withdrawal::find($withdrawalId);
                
                if ($withdrawal && $withdrawal->status === 'pending') {
                    $withdrawal->update(['status' => 'approved']);
                    
                    \Illuminate\Support\Facades\Log::info('HeartPay Webhook: Withdrawal approved successfully', [
                        'withdrawal_id' => $withdrawalId
                    ]);
                    
                    return response()->json(['success' => true, 'message' => 'Saque concluído com sucesso!'], 200);
                } else {
                    \Illuminate\Support\Facades\Log::warning('HeartPay Webhook: Withdrawal not found or not pending', [
                        'withdrawal_id' => $withdrawalId,
                        'status' => $withdrawal ? $withdrawal->status : 'not_found'
                    ]);
                }
            }
        } elseif (in_array($event, ['PAYOUT_FAILED', 'PayOutFailed'])) {
            // Check if it is a withdrawal
            if (strpos($correlationID, 'withdrawal_id_') === 0) {
                $withdrawalId = intval(substr($correlationID, 14));
                $withdrawal = \App\Models\Withdrawal::find($withdrawalId);
                
                if ($withdrawal && $withdrawal->status === 'pending') {
                    \Illuminate\Support\Facades\DB::transaction(function() use ($withdrawal) {
                        $withdrawal->update(['status' => 'rejected']); // rejected/failed
                        $user = $withdrawal->user;
                        if ($user) {
                            $user->balance += $withdrawal->amount; // Refund user
                            $user->save();
                        }
                    });
                    
                    \Illuminate\Support\Facades\Log::info('HeartPay Webhook: Withdrawal failed, refunded successfully', [
                        'withdrawal_id' => $withdrawalId,
                        'amount' => $withdrawal->amount,
                        'user_id' => $withdrawal->user_id
                    ]);
                    
                    return response()->json(['success' => true, 'message' => 'Saque falhou, saldo estornado!'], 200);
                } else {
                    \Illuminate\Support\Facades\Log::warning('HeartPay Webhook: Withdrawal not found or not pending (for failure handler)', [
                        'withdrawal_id' => $withdrawalId,
                        'status' => $withdrawal ? $withdrawal->status : 'not_found'
                    ]);
                }
            }
        }
        
        \Illuminate\Support\Facades\Log::info('HeartPay Webhook: Event ignored or already processed', [
            'event' => $event,
            'correlationID' => $correlationID
        ]);
        
        return response()->json(['success' => true, 'message' => 'Evento ignorado ou já processado.'], 200);
    }

    /**
     * Check the status of a specific deposit.
     */
    public function checkStatus($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa estar logado para realizar esta ação.'
            ], 401);
        }

        $deposit = Deposit::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$deposit) {
            return response()->json([
                'success' => false,
                'message' => 'Depósito não encontrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $deposit->status, // 'pending', 'approved', 'rejected'
        ]);
    }

    /**
     * Request a new withdrawal.
     */
    public function requestWithdrawal(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa estar logado para realizar esta ação.'
            ], 401);
        }

        $minWithdrawal = floatval(Setting::get('min_withdrawal', '50.00'));
        $maxWithdrawal = floatval(Setting::get('max_withdrawal', '5000.00'));

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:' . $minWithdrawal . '|max:' . $maxWithdrawal,
            'pix_key' => 'required|string|max:255',
        ], [
            'amount.min' => 'O valor mínimo de saque é R$ ' . number_format($minWithdrawal, 2, ',', '.'),
            'amount.max' => 'O valor máximo de saque é R$ ' . number_format($maxWithdrawal, 2, ',', '.'),
            'amount.required' => 'O valor é obrigatório.',
            'amount.numeric' => 'O valor informado deve ser um número válido.',
            'pix_key.required' => 'A chave Pix é obrigatória.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Enforce rollover verification before withdrawal
        if ($user->bonus_balance > 0 && $user->bonus_rollover_current < $user->bonus_rollover_target) {
            $formattedCurrent = number_format($user->bonus_rollover_current, 2, ',', '.');
            $formattedTarget = number_format($user->bonus_rollover_target, 2, ',', '.');
            return response()->json([
                'success' => false,
                'message' => "Você possui bônus ativo e ainda não atingiu a meta de rollover. Meta: R$ {$formattedTarget} (Apostado: R$ {$formattedCurrent})."
            ], 422);
        }
        
        // Double check KYC status for withdrawal
        if ($user->kyc_status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Sua conta precisa estar verificada (KYC aprovado) para realizar saques.'
            ], 422);
        }

        $amount = floatval($request->input('amount'));

        if ($user->balance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo insuficiente para realizar este saque.'
            ], 422);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function() use ($user, $amount, $request) {
                // Deduct balance
                $user->balance -= $amount;
                $user->save();

                // Create pending withdrawal
                \App\Models\Withdrawal::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'status' => 'pending',
                    'pix_key' => trim($request->input('pix_key')),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Solicitação de saque enviada com sucesso! O valor foi debitado de seu saldo e será processado pelo administrador.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar saque: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get deposit and withdrawal history for the logged-in user.
     */
    public function getTransactionHistory(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autorizado.'
            ], 401);
        }

        $userId = Auth::id();

        $deposits = Deposit::with('user')->where('user_id', $userId)
            ->select('id', 'amount', 'status', 'created_at')
            ->get()
            ->map(function ($item) {
                $statusText = 'Pendente';
                $badgeClass = 'pending';
                if ($item->status === 'approved') {
                    $statusText = 'Aprovado';
                    $badgeClass = 'approved';
                } elseif ($item->status === 'rejected' || $item->status === 'declined' || $item->status === 'failed') {
                    $statusText = 'Recusado';
                    $badgeClass = 'rejected';
                }

                return [
                    'id' => $item->id,
                    'type' => 'Depósito',
                    'amount' => $item->amount,
                    'amount_formatted' => '+ R$ ' . number_format($item->amount, 2, ',', '.'),
                    'amount_color' => '#00ff88',
                    'status_text' => $statusText,
                    'badge_class' => $badgeClass,
                    'date' => $item->created_at->format('d/m/Y H:i'),
                    'timestamp' => $item->created_at->timestamp
                ];
            });

        $withdrawals = \App\Models\Withdrawal::where('user_id', $userId)
            ->select('id', 'amount', 'status', 'created_at')
            ->get()
            ->map(function ($item) {
                $statusText = 'Em análise';
                $badgeClass = 'pending';
                if ($item->status === 'approved') {
                    $statusText = 'Pago';
                    $badgeClass = 'approved';
                } elseif ($item->status === 'rejected' || $item->status === 'failed') {
                    $statusText = 'Recusado';
                    $badgeClass = 'rejected';
                }

                return [
                    'id' => $item->id,
                    'type' => 'Saque',
                    'amount' => $item->amount,
                    'amount_formatted' => '- R$ ' . number_format($item->amount, 2, ',', '.'),
                    'amount_color' => '#ff3d00',
                    'status_text' => $statusText,
                    'badge_class' => $badgeClass,
                    'date' => $item->created_at->format('d/m/Y H:i'),
                    'timestamp' => $item->created_at->timestamp
                ];
            });

        $bets = \App\Models\Bet::where('user_id', $userId)
            ->select('id', 'game', 'bet_amount', 'payout', 'multiplier', 'status', 'created_at')
            ->get()
            ->map(function ($item) {
                $gameName = ucfirst(str_replace('_', ' ', $item->game));
                $isWin = ($item->status === 'win');
                
                $amountColor = $isWin ? '#00e676' : '#ff3d00';
                $amountFormatted = $isWin 
                    ? '+ R$ ' . number_format($item->payout, 2, ',', '.') 
                    : '- R$ ' . number_format($item->bet_amount, 2, ',', '.');
                    
                $statusText = $isWin ? 'Ganhou (' . number_format($item->multiplier, 1, ',', '.') . 'x)' : 'Perdeu';
                $badgeClass = $isWin ? 'approved' : 'rejected';

                if ($item->status === 'pending') {
                    $statusText = 'Em andamento';
                    $badgeClass = 'pending';
                    $amountColor = '#ffffff';
                    $amountFormatted = 'R$ ' . number_format($item->bet_amount, 2, ',', '.');
                }

                return [
                    'id' => $item->id,
                    'type' => 'Aposta (' . $gameName . ')',
                    'amount' => $item->bet_amount,
                    'amount_formatted' => $amountFormatted,
                    'amount_color' => $amountColor,
                    'status_text' => $statusText,
                    'badge_class' => $badgeClass,
                    'date' => $item->created_at->format('d/m/Y H:i'),
                    'timestamp' => $item->created_at->timestamp
                ];
            });

        // Merge and sort by timestamp desc
        $history = $deposits->concat($withdrawals)->concat($bets)->sortByDesc('timestamp')->values()->all();

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }
}
