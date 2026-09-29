<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KycController extends Controller
{
    /**
     * Create a verification session with Didit.
     */
    public function createSession(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado.'], 401);
        }

        $apiKey = Setting::get('didit_api_key', '');
        $workflowId = Setting::get('didit_workflow_id', 'bcf57002-cb39-49fe-9707-46e32650275b');

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'A integração do KYC não está configurada (API Key ausente). Por favor, avise o administrador.'
            ], 500);
        }

        // Call Didit API
        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://verification.didit.me/v3/session/', [
                'workflow_id' => $workflowId,
                'vendor_data' => (string) $user->id,
                'callback' => route('kyc.webhook'),
            ]);

            if ($response->successful()) {
                $url = $response->json('url');
                
                // Update user session URL but keep status as is until submitted
                $user->kyc_session_url = $url;
                $user->save();

                return response()->json([
                    'success' => true,
                    'url' => $url
                ]);
            }

            Log::error('Didit API Error response: ' . $response->body());
            return response()->json([
                'success' => false,
                'message' => 'Erro retornado pela Didit: ' . ($response->json('message') ?? 'Erro desconhecido')
            ], 500);

        } catch (\Exception $e) {
            Log::error('Exception calling Didit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Falha de comunicação com o serviço Didit. Tente novamente mais tarde.'
            ], 500);
        }
    }

    /**
     * Set status to pending when user completes SDK flow.
     */
    public function submitSession(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->kyc_status = 'pending';
            $user->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Usuário não autenticado.'], 401);
    }

    /**
     * Receive webhook notifications from Didit.
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Didit Webhook received: ', $request->all());

        $userId = $request->input('vendor_data');
        $status = $request->input('status'); // e.g. "Approved", "Declined", "Pending"

        if (!$userId || !$status) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Map Didit status to local status
        $statusMap = [
            'approved' => 'approved',
            'declined' => 'declined',
            'pending' => 'pending',
            'expired' => 'declined'
        ];

        $mappedStatus = $statusMap[strtolower($status)] ?? 'pending';

        $user->kyc_status = $mappedStatus;
        $user->save();

        Log::info("User ID {$userId} KYC status updated to {$mappedStatus} via Didit Webhook.");

        return response()->json(['status' => 'success']);
    }

    /**
     * Show KYC verification list for admin panel.
     */
    public function adminIndex(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $search = $request->input('search');
        $status = $request->input('status');

        $query = User::query();

        // Sort by users who started verification (pending first, then approved/declined)
        if ($status) {
            $query->where('kyc_status', $status);
        } else {
            // default: list users with some KYC activity first
            $query->orderByRaw("FIELD(kyc_status, 'pending', 'declined', 'approved', 'not_started', NULL)");
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);

        return view('admin.kyc', compact('users', 'search', 'status'));
    }

    /**
     * Update user KYC status manually by admin.
     */
    public function adminUpdateStatus(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'status' => 'required|in:approved,pending,declined,not_started'
        ]);

        $user = User::findOrFail($id);
        $user->kyc_status = $request->input('status');
        $user->save();

        return back()->with('success', "Status KYC do usuário {$user->name} alterado com sucesso para " . ucfirst($user->kyc_status) . "!");
    }
}
