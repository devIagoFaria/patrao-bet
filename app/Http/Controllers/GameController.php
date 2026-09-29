<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bet;
use App\Models\User;
use App\Models\CategoryButton;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{
    /**
     * Deduct balance and create a pending bet.
     */
    public function placeBet(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa estar logado para jogar.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'game' => 'required|string|in:aviator,mines,fortune_tiger',
            'bet_amount' => 'required|numeric|min:0.1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $betAmount = floatval($request->bet_amount);

        if ($user->balance < $betAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo insuficiente.'
            ], 400);
        }

        try {
            $bet = DB::transaction(function () use ($user, $request, $betAmount) {
                // Deduct balance
                $user->balance -= $betAmount;
                $user->save();

                // Create pending bet
                return Bet::create([
                    'user_id' => $user->id,
                    'game' => $request->game,
                    'bet_amount' => $betAmount,
                    'multiplier' => null,
                    'payout' => 0.00,
                    'status' => 'pending',
                ]);
            });

            // Track progression for tournaments and missions
            try {
                \App\Helpers\ProgressionTracker::trackBet($user->id, $request->game, $betAmount);
            } catch (\Exception $ex) {
                \Illuminate\Support\Facades\Log::error('Missions/Tournaments tracking error: ' . $ex->getMessage());
            }

            return response()->json([
                'success' => true,
                'bet_id' => $bet->id,
                'balance' => $user->balance,
                'balance_formatted' => number_format($user->balance, 2, ',', '.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar aposta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process game outcome, credit balance if won, update bet record.
     */
    public function claimPayout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autenticado.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'bet_id' => 'required|exists:bets,id',
            'multiplier' => 'required|numeric|min:0',
            'status' => 'required|string|in:win,lose',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $bet = Bet::where('id', $request->bet_id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$bet) {
            return response()->json([
                'success' => false,
                'message' => 'Aposta não encontrada ou já processada.'
            ], 404);
        }

        $status = $request->status;
        $multiplier = floatval($request->multiplier);
        $payout = 0.00;

        if ($status === 'win') {
            $payout = $bet->bet_amount * $multiplier;
        }

        try {
            DB::transaction(function () use ($user, $bet, $status, $multiplier, $payout) {
                // Update bet
                $bet->update([
                    'multiplier' => $multiplier,
                    'payout' => $payout,
                    'status' => $status,
                ]);

                // If won, credit balance
                if ($status === 'win' && $payout > 0) {
                    $user->balance += $payout;
                    $user->save();
                }
            });

            return response()->json([
                'success' => true,
                'balance' => $user->balance,
                'balance_formatted' => number_format($user->balance, 2, ',', '.'),
                'payout' => $payout,
                'payout_formatted' => number_format($payout, 2, ',', '.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar resultado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve the current balance.
     */
    public function getBalance()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'balance' => 0]);
        }

        return response()->json([
            'success' => true,
            'balance' => Auth::user()->balance,
            'balance_formatted' => number_format(Auth::user()->balance, 2, ',', '.')
        ]);
    }

    /**
     * Launch an external game (PlayFiver).
     */
    public function launch(Request $request, $slug)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['status' => false, 'msg' => 'Você precisa estar logado para jogar.'], 401);
            }
            return redirect()->route('home')->with('error', 'Você precisa estar logado para jogar.');
        }

        $game = \App\Models\CategoryButton::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        if ($game->distribution === 'playfiver') {
            $service = new \App\Helpers\PlayFiverService();
            
            // Launch parameters
            $gameCode = $game->action_value;
            $provider = $game->subtitle; // stored provider name/id
            $userCode = $user->id;
            $userBalance = $user->balance;
            $isOriginal = $game->is_original;
            
            Log::info("Iniciando lançamento de jogo PlayFiver: {$game->title} ({$gameCode}) para usuário {$user->id}");
            
            $result = $service->gameLaunch($gameCode, $provider, $userCode, $userBalance, $isOriginal);
            
            if (isset($result['status']) && $result['status'] === true && isset($result['game_url'])) {
                Log::info("Jogo lançado com sucesso: {$result['game_url']}");
                if ($request->ajax()) {
                    return response()->json(['status' => true, 'game_url' => $result['game_url']]);
                }
                return view('game.launch', [
                    'gameUrl' => $result['game_url'],
                    'gameTitle' => $game->title
                ]);
            }

            if ($request->ajax()) {
                return response()->json(['status' => false, 'msg' => $result['msg'] ?? 'Erro na API']);
            }
            return redirect()->route('home')->with('error', 'Falha ao iniciar o jogo: ' . ($result['msg'] ?? 'Erro na API'));
        }

        if ($game->distribution === 'maxapigames') {
            $service = new \App\Helpers\MaxApiGamesService();

            $gameCode = $game->action_value;
            $userCode = $user->id;
            $callbackUrl = route('webhook.maxapigames');

            Log::info("Iniciando lançamento de jogo MAX API GAMES: {$game->title} ({$gameCode}) para usuário {$user->id}");

            $result = $service->gameLaunch($userCode, $gameCode, $callbackUrl);

            if (isset($result['status']) && $result['status'] === true && isset($result['game_url'])) {
                Log::info("Jogo lançado com sucesso: {$result['game_url']}");
                if ($request->ajax()) {
                    return response()->json(['status' => true, 'game_url' => $result['game_url']]);
                }
                return view('game.launch', [
                    'gameUrl' => $result['game_url'],
                    'gameTitle' => $game->title
                ]);
            }

            if ($request->ajax()) {
                return response()->json(['status' => false, 'msg' => $result['msg'] ?? 'Erro na API']);
            }
            return redirect()->route('home')->with('error', 'Falha ao iniciar o jogo: ' . ($result['msg'] ?? 'Erro na API'));
        }

        if ($request->ajax()) {
            return response()->json(['status' => false, 'msg' => 'Este jogo não está disponível para lançamento externo.']);
        }
        return redirect()->route('home')->with('error', 'Este jogo não está disponível para lançamento externo.');
    }

    /**
     * Launch Sportsbook (PlayFiver).
     */
    public function sports(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Você precisa estar logado para apostar em esportes.');
        }

        $user = Auth::user();
        $service = new \App\Helpers\PlayFiverService();
        
        // Launch parameters for Sportsbook
        $gameCode = 'sport'; // Código correto da API PlayFiver
        $provider = 'DIGITAIN OFICIAL'; // Provedor correto da API PlayFiver
        $userCode = $user->id;
        $userBalance = $user->balance;
        
        // Launch with isSport = true
        $result = $service->gameLaunch($gameCode, $provider, $userCode, $userBalance, true, 70, 'pt', true);
        
        if (isset($result['status']) && $result['status'] === true && isset($result['game_url'])) {
            return view('game.launch', [
                'gameUrl' => $result['game_url'],
                'gameTitle' => 'Esportes'
            ]);
        }

        return redirect()->route('home')->with('error', 'Falha ao iniciar Esportes: ' . ($result['msg'] ?? 'Erro na API'));
    }

    /**
     * Get active tournaments with user status and leaderboard.
     */
    public function getActiveTournaments()
    {
        $now = \Carbon\Carbon::now();
        $tournaments = \App\Models\Tournament::where('is_active', true)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get();

        $userId = Auth::id();

        $data = $tournaments->map(function ($t) use ($userId) {
            // Fetch top 15 leaderboard
            $participants = \App\Models\TournamentParticipant::where('tournament_id', $t->id)
                ->orderBy('score', 'desc')
                ->with('user')
                ->limit(15)
                ->get()
                ->map(function ($p, $idx) {
                    $name = $p->user->name ?? 'Jogador';
                    if (strlen($name) <= 4) {
                        $obfuscatedName = substr($name, 0, 1) . '***' . substr($name, -1);
                    } else {
                        $obfuscatedName = substr($name, 0, 2) . '***' . substr($name, -2);
                    }
                    return [
                        'rank' => $idx + 1,
                        'name' => $obfuscatedName,
                        'score' => number_format($p->score, 2, ',', '.'),
                    ];
                });

            $userScore = 0.00;
            $userRank = '-';
            
            if ($userId) {
                $myPart = \App\Models\TournamentParticipant::where('tournament_id', $t->id)
                    ->where('user_id', $userId)
                    ->first();
                if ($myPart) {
                    $userScore = number_format($myPart->score, 2, ',', '.');
                    // Calculate rank
                    $userRank = \App\Models\TournamentParticipant::where('tournament_id', $t->id)
                        ->where('score', '>', $myPart->score)
                        ->count() + 1;
                }
            }

            return [
                'id' => $t->id,
                'title' => $t->title,
                'description' => $t->description,
                'prize_pool' => number_format($t->prize_pool, 2, ',', '.'),
                'banner_url' => asset($t->banner_path),
                'end_date' => $t->end_date,
                'user_score' => $userScore,
                'user_rank' => $userRank,
                'leaderboard' => $participants
            ];
        });

        return response()->json(['success' => true, 'tournaments' => $data]);
    }

    /**
     * Dynamic search for games and providers.
     */
    public function searchGames(Request $request)
    {
        $query = $request->query('q');
        
        if (strlen($query) < 2) {
            return response()->json(['success' => true, 'games' => []]);
        }

        $games = \App\Models\CategoryButton::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('subtitle', 'like', "%{$query}%")
                  ->orWhere('slug', 'like', "%{$query}%");
            })
            ->select('slug', 'title', 'subtitle', 'image_path', 'action_type', 'action_value', 'distribution')
            ->groupBy('slug', 'title', 'subtitle', 'image_path', 'action_type', 'action_value', 'distribution')
            ->limit(24)
            ->get();

        return response()->json([
            'success' => true, 
            'games' => $games
        ]);
    }

    /**
     * Get active missions for the user.
     */
    public function getActiveMissions()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Não autenticado'], 401);
        }

        $userId = Auth::id();
        $missions = \App\Models\Mission::where('is_active', true)->get();

        $data = $missions->map(function ($m) use ($userId) {
            $progress = \App\Helpers\ProgressionTracker::getOrInitializeProgress($userId, $m);

            return [
                'id' => $m->id,
                'title' => $m->title,
                'description' => $m->description,
                'type' => $m->type,
                'target_value' => (float)$m->target_value,
                'reward_amount' => number_format($m->reward_amount, 2, ',', '.'),
                'period' => $m->period,
                'current_value' => (float)$progress->current_value,
                'is_completed' => (bool)$progress->is_completed,
                'reward_claimed' => (bool)$progress->reward_claimed,
                'reset_at' => $progress->reset_at ? $progress->reset_at->toIso8601String() : null,
            ];
        });

        return response()->json([
            'success' => true,
            'missions' => $data
        ]);
    }

    /**
     * Claim mission reward.
     */
    public function claimMissionReward(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Não autenticado'], 401);
        }

        $validator = Validator::make($request->all(), [
            'mission_id' => 'required|exists:missions,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Dados inválidos.'], 422);
        }

        $userId = Auth::id();
        $missionId = $request->mission_id;

        $mission = \App\Models\Mission::find($missionId);
        if (!$mission || !$mission->is_active) {
            return response()->json(['success' => false, 'message' => 'Missão inativa ou não encontrada.'], 404);
        }

        $progress = \App\Models\UserMissionProgress::where('user_id', $userId)
            ->where('mission_id', $missionId)
            ->first();

        if (!$progress) {
            return response()->json(['success' => false, 'message' => 'Nenhum progresso encontrado para esta missão.'], 404);
        }

        // Handle possible expired state
        if ($progress->reset_at && \Carbon\Carbon::now()->greaterThan($progress->reset_at)) {
            // Progress has expired, reset it
            DB::transaction(function() use ($progress, $mission) {
                $progress->current_value = 0.00;
                $progress->is_completed = false;
                $progress->reward_claimed = false;
                $progress->reset_at = $mission->period === 'daily' ? \Carbon\Carbon::now()->endOfDay() : ($mission->period === 'weekly' ? \Carbon\Carbon::now()->endOfWeek() : null);
                $progress->save();
            });
            return response()->json(['success' => false, 'message' => 'Esta missão expirou e foi reiniciada. Faça a missão novamente para ganhar o prêmio!'], 400);
        }

        if (!$progress->is_completed) {
            return response()->json(['success' => false, 'message' => 'Missão ainda não concluída.'], 400);
        }

        if ($progress->reward_claimed) {
            return response()->json(['success' => false, 'message' => 'Você já resgatou o prêmio desta missão.'], 400);
        }

        try {
            $user = Auth::user();
            DB::transaction(function() use ($progress, $user, $mission) {
                // Mark claimed
                $progress->reward_claimed = true;
                $progress->save();

                // Add balance
                $user->balance += $mission->reward_amount;
                $user->save();
            });

            return response()->json([
                'success' => true,
                'message' => 'Parabéns! R$ ' . number_format($mission->reward_amount, 2, ',', '.') . ' foram creditados na sua conta!',
                'balance' => $user->balance,
                'balance_formatted' => number_format($user->balance, 2, ',', '.')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao resgatar recompensa: ' . $e->getMessage()], 500);
        }
    }
}
