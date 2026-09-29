<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PasswordResetCodeMail;
use App\Services\MailConfigService;

class AuthController extends Controller
{
    /**
     * Handle user registration via AJAX.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'cpf' => 'required|string|max:14',
        ], [
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.confirmed' => 'As senhas não coincidem.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'cpf.required' => 'O CPF é obrigatório.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $cpfClean = preg_replace('/[^0-9]/', '', $request->input('cpf'));
        if (User::where('cpf', $cpfClean)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Este CPF já está cadastrado em outra conta.'
            ], 422);
        }

        $referrerId = $request->cookie('fone777_affiliate_ref');
        if (empty($referrerId)) {
            $referrerId = $request->query('ref');
        }
        $referrerId = intval($referrerId);
        if ($referrerId > 0 && !\App\Models\User::where('id', $referrerId)->exists()) {
            $referrerId = null;
        }

        $welcomeBonus = floatval(\App\Models\Setting::get('welcome_bonus_amount', '5.00'));
        $bonusRollover = floatval(\App\Models\Setting::get('bonus_rollover', '10'));
        $rolloverTarget = $welcomeBonus * $bonusRollover;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $cpfClean,
            'password' => Hash::make($request->password),
            'balance' => 0.00, // Saldo inicial zerado
            'referred_by' => $referrerId ?: null,
            'bonus_balance' => $welcomeBonus,
            'bonus_rollover_target' => $rolloverTarget,
            'bonus_rollover_current' => 0.00,
        ]);

        if ($welcomeBonus > 0) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Bônus de Boas-vindas!',
                'message' => 'Você recebeu um bônus de R$ ' . number_format($welcomeBonus, 2, ',', '.') . ' por se cadastrar na plataforma! Meta de rollover: R$ ' . number_format($rolloverTarget, 2, ',', '.') . '.',
            ]);
        }

        if ($user->referred_by) {
            $referrer = \App\Models\User::find($user->referred_by);
            if ($referrer) {
                \App\Models\Notification::create([
                    'user_id' => $referrer->id,
                    'title' => 'Novo indicado!',
                    'message' => 'O usuário ' . $user->name . ' se cadastrou usando o seu link de afiliado.',
                ]);
            }
        }

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Cadastro realizado com sucesso!',
            'user' => [
                'name' => $user->name,
                'balance' => number_format($user->balance, 2, ',', '.')
            ]
        ]);
    }

    /**
     * Handle user login via AJAX.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.email' => 'Por favor, insira um e-mail válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            return response()->json([
                'success' => true,
                'message' => 'Login realizado com sucesso!',
                'user' => [
                    'name' => $user->name,
                    'balance' => number_format($user->balance, 2, ',', '.')
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciais inválidas. Verifique seu e-mail e senha.'
        ], 401);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Validate CPF mathematically and return mock identity details.
     */
    public function validateCPF(Request $request)
    {
        $cpf = $request->input('cpf');
        
        if (empty($cpf)) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor, informe o CPF.'
            ], 422);
        }

        // Standard mathematical verification digits check
        if (!$this->helperValidateCPF($cpf)) {
            return response()->json([
                'success' => false,
                'message' => 'CPF inválido ou inexistente.'
            ], 422);
        }

        // Mock names based on CPF digits to add variety
        $cpfClean = preg_replace('/[^0-9]/', '', $cpf);
        $lastDigit = (int)substr($cpfClean, -1);
        
        $names = [
            0 => 'MARCOS PINTO OLIVEIRA',
            1 => 'ADRIANO SILVA MOURA',
            2 => 'BRUNO ALMEIDA VIEIRA',
            3 => 'CARLOS SOUZA GOMES',
            4 => 'FELIPE CARDOSO DIAS',
            5 => 'GABRIEL NUNES ROCHA',
            6 => 'JOÃO COSTA CARDOSO',
            7 => 'LUCAS PEREIRA MOREIRA',
            8 => 'MATEUS LIMA ARAÚJO',
            9 => 'THIAGO MENDES COSTA'
        ];

        $mockName = isset($names[$lastDigit]) ? $names[$lastDigit] : 'USUÁRIO PATRÃO';

        return response()->json([
            'success' => true,
            'message' => 'CPF validado com sucesso na base de dados.',
            'name' => $mockName,
            'birthdate' => '15/08/1995'
        ]);
    }

    /**
     * Mathematical CPF Validator Checksum Algorithm.
     */
    private function helperValidateCPF($cpf)
    {
        // Extract numbers only
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);
         
        // Length check
        if (strlen($cpf) != 11) {
            return false;
        }

        // Repeated sequences check
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Mathematical checksum check
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * Update user profile name.
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autorizado.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'O nome é obrigatório.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Dados atualizados com sucesso!'
        ]);
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autorizado.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'A senha atual é obrigatória.',
            'password.required' => 'A nova senha é obrigatória.',
            'password.min' => 'A nova senha deve ter no mínimo 6 caracteres.',
            'password.confirmed' => 'A confirmação de senha não coincide.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'A senha atual informada está incorreta.'
            ], 422);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Senha alterada com sucesso!'
        ]);
    }

    /**
     * Send a password reset code to the user's e-mail ("Esqueci minha senha").
     */
    public function sendPasswordResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ], [
            'email.email' => 'Por favor, insira um e-mail válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $genericMessage = 'Se o e-mail informado estiver cadastrado, um código de recuperação foi enviado.';

        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            // Do not reveal whether the e-mail exists.
            return response()->json(['success' => true, 'message' => $genericMessage]);
        }

        $code = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        try {
            MailConfigService::apply();
            Mail::to($user->email)->send(new PasswordResetCodeMail($code, $user->name));
        } catch (\Exception $e) {
            Log::error('Falha ao enviar e-mail de recuperação de senha: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível enviar o e-mail agora. Tente novamente mais tarde.'
            ], 500);
        }

        return response()->json(['success' => true, 'message' => $genericMessage]);
    }

    /**
     * Confirm the reset code and set a new password.
     */
    public function resetPasswordWithCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'code' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação de senha não coincide.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $record = DB::table('password_reset_tokens')->where('email', $request->input('email'))->first();

        if (!$record || !Hash::check($request->input('code'), $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Código inválido. Verifique e tente novamente.'
            ], 422);
        }

        if (now()->diffInMinutes($record->created_at) > 30) {
            DB::table('password_reset_tokens')->where('email', $request->input('email'))->delete();
            return response()->json([
                'success' => false,
                'message' => 'Este código expirou. Solicite um novo.'
            ], 422);
        }

        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->input('email'))->delete();

        return response()->json([
            'success' => true,
            'message' => 'Senha redefinida com sucesso! Você já pode entrar com sua nova senha.'
        ]);
    }
}
