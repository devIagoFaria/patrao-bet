<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Validate coupon code for user deposit.
     */
    public function validateCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Código de cupom ou valor inválido.'
            ], 422);
        }

        $code = trim($request->input('code'));
        $amount = floatval($request->input('amount'));

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Cupom inválido ou não encontrado.'
            ], 404);
        }

        if (!$coupon->isValidFor($amount)) {
            $msg = 'Este cupom não está ativo ou já expirou.';
            if ($amount < $coupon->min_deposit) {
                $msg = 'Este cupom exige um valor mínimo de depósito de R$ ' . number_format($coupon->min_deposit, 2, ',', '.') . '.';
            } elseif ($coupon->max_uses !== null && $coupon->uses_count >= $coupon->max_uses) {
                $msg = 'Este cupom atingiu o limite máximo de utilizações.';
            }
            return response()->json([
                'success' => false,
                'message' => $msg
            ], 422);
        }

        $bonusValueFormatted = ($coupon->type === 'percentage') ? $coupon->value . '%' : 'R$ ' . number_format($coupon->value, 2, ',', '.');

        return response()->json([
            'success' => true,
            'message' => 'Cupom validado com sucesso!',
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'value_formatted' => $bonusValueFormatted,
            ]
        ]);
    }

    /**
     * Display admin list of coupons.
     */
    public function index()
    {
        $coupons = Coupon::orderBy('id', 'desc')->get();
        return view('admin.coupons', compact('coupons'));
    }

    /**
     * Store new coupon.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0.01',
            'min_deposit' => 'required|numeric|min:0.00',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $expiresAt = null;
        if ($request->filled('expires_at')) {
            $expiresAt = Carbon::parse($request->input('expires_at'))->endOfDay();
        }

        Coupon::create([
            'code' => strtoupper(trim($request->input('code'))),
            'type' => $request->input('type'),
            'value' => $request->input('value'),
            'min_deposit' => $request->input('min_deposit'),
            'max_uses' => $request->input('max_uses'),
            'is_active' => $request->has('is_active'),
            'expires_at' => $expiresAt,
        ]);

        return back()->with('success', 'Cupom cadastrado com sucesso!');
    }

    /**
     * Update coupon.
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0.01',
            'min_deposit' => 'required|numeric|min:0.00',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $expiresAt = null;
        if ($request->filled('expires_at')) {
            $expiresAt = Carbon::parse($request->input('expires_at'))->endOfDay();
        }

        $coupon->update([
            'code' => strtoupper(trim($request->input('code'))),
            'type' => $request->input('type'),
            'value' => $request->input('value'),
            'min_deposit' => $request->input('min_deposit'),
            'max_uses' => $request->input('max_uses'),
            'is_active' => $request->has('is_active'),
            'expires_at' => $expiresAt,
        ]);

        return back()->with('success', 'Cupom atualizado com sucesso!');
    }

    /**
     * Delete coupon.
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return back()->with('success', 'Cupom removido com sucesso!');
    }
}
