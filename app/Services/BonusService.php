<?php

namespace App\Services;

use App\Models\User;
use App\Models\Deposit;
use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BonusService
{
    /**
     * Process all bonuses (progressive, coupons) and referral commissions when a deposit is approved.
     */
    public static function processDepositBonus(Deposit $deposit)
    {
        try {
            DB::transaction(function () use ($deposit) {
                $user = $deposit->user;
                if (!$user) return;

                // 1. Calculate Deposit Sequence Number
                // We count how many deposits were approved before this one (or exclude this one if status is already updated)
                $approvedCount = Deposit::where('user_id', $user->id)
                    ->where('status', 'approved')
                    ->where('id', '!=', $deposit->id)
                    ->count();

                $depositNum = $approvedCount + 1;

                // 2. Calculate Progressive Deposit Bonus
                $progressivePercent = 0;
                if ($depositNum <= 4) {
                    $progressivePercent = floatval(Setting::get("progressive_bonus_{$depositNum}", '100'));
                }
                $progressiveBonus = $deposit->amount * ($progressivePercent / 100);

                // 3. Calculate Coupon Bonus
                $couponBonus = 0.00;
                if ($deposit->coupon_id) {
                    $coupon = Coupon::find($deposit->coupon_id);
                    if ($coupon && $coupon->isValidFor($deposit->amount)) {
                        if ($coupon->type === 'percentage') {
                            $couponBonus = $deposit->amount * ($coupon->value / 100);
                        } else {
                            $couponBonus = $coupon->value;
                        }

                        // Update coupon use
                        $coupon->increment('uses_count');

                        // Save user_coupons record
                        UserCoupon::create([
                            'user_id' => $user->id,
                            'coupon_id' => $coupon->id,
                        ]);

                        // Save coupon bonus to deposit record
                        $deposit->update([
                            'coupon_bonus' => $couponBonus,
                        ]);
                    }
                }

                // 4. Apply Bonuses to User Balance
                $totalBonus = $progressiveBonus + $couponBonus;
                if ($totalBonus > 0) {
                    $rolloverMultiplier = floatval(Setting::get('bonus_rollover', '10'));
                    $rolloverAdded = $totalBonus * $rolloverMultiplier;

                    $user->bonus_balance += $totalBonus;
                    $user->bonus_rollover_target += $rolloverAdded;
                    $user->save();

                    // Generate user notification
                    $msgParts = [];
                    if ($progressiveBonus > 0) {
                        $msgParts[] = "Bônus Progressivo de Depósito (" . $progressivePercent . "%): R$ " . number_format($progressiveBonus, 2, ',', '.');
                    }
                    if ($couponBonus > 0) {
                        $msgParts[] = "Bônus do Cupom: R$ " . number_format($couponBonus, 2, ',', '.');
                    }

                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Bônus de Depósito Creditado!',
                        'message' => implode(" | ", $msgParts) . ". Rollover adicionado: R$ " . number_format($rolloverAdded, 2, ',', '.') . ".",
                    ]);
                }

                // 5. Calculate and Apply Referral Commission
                if ($user->referred_by) {
                    $referrer = User::find($user->referred_by);
                    if ($referrer) {
                        $commissionAmount = 0.00;
                        $commissionLabel = '';

                        if ($referrer->commission_model === 'cpa') {
                            // CPA: fixed one-time payment, paid only on the referral's first approved deposit.
                            if ($depositNum === 1 && $referrer->commission_value) {
                                $commissionAmount = floatval($referrer->commission_value);
                                $commissionLabel = "CPA fixo de R$ " . number_format($commissionAmount, 2, ',', '.');
                            }
                        } else {
                            $commissionPercent = $referrer->commission_value !== null
                                ? floatval($referrer->commission_value)
                                : floatval(Setting::get('referral_commission_percent', '10'));
                            if ($commissionPercent > 0) {
                                $commissionAmount = $deposit->amount * ($commissionPercent / 100);
                                $commissionLabel = number_format($commissionPercent, 2, ',', '.') . "% de comissão (revshare)";
                            }
                        }

                        if ($commissionAmount > 0) {
                            $referrer->affiliate_commission_unpaid += $commissionAmount;
                            $referrer->save();

                            Notification::create([
                                'user_id' => $referrer->id,
                                'title' => 'Comissão de Afiliado recebida!',
                                'message' => "Você recebeu R$ " . number_format($commissionAmount, 2, ',', '.') . " ({$commissionLabel}) no seu painel de afiliados porque seu indicado " . $user->name . " fez um depósito de R$ " . number_format($deposit->amount, 2, ',', '.') . ".",
                            ]);
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Error in BonusService::processDepositBonus: ' . $e->getMessage());
        }
    }
}
