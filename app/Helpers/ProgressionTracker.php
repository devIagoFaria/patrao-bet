<?php

namespace App\Helpers;

use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\Mission;
use App\Models\UserMissionProgress;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProgressionTracker
{
    /**
     * Track user progress for bets placed.
     */
    public static function trackBet($userId, $gameSlug, $betAmount)
    {
        try {
            DB::transaction(function () use ($userId, $gameSlug, $betAmount) {
                $now = Carbon::now();

                // 1. Process Active Tournaments
                $activeTournaments = Tournament::where('is_active', true)
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now)
                    ->get();

                foreach ($activeTournaments as $tournament) {
                    $games = $tournament->games;
                    // Check if current game is eligible for this tournament
                    if (is_array($games) && in_array($gameSlug, $games)) {
                        $participant = TournamentParticipant::firstOrCreate(
                            [
                                'tournament_id' => $tournament->id,
                                'user_id' => $userId
                            ],
                            [
                                'score' => 0.00
                            ]
                        );
                        $participant->score += $betAmount;
                        $participant->save();
                    }
                }

                // 2. Process Active Missions (bet_count, bet_amount)
                $activeMissions = Mission::where('is_active', true)
                    ->whereIn('type', ['bet_count', 'bet_amount'])
                    ->where(function ($query) use ($gameSlug) {
                        $query->whereNull('game_slug')
                              ->orWhere('game_slug', $gameSlug);
                    })
                    ->get();

                foreach ($activeMissions as $mission) {
                    $progress = self::getOrInitializeProgress($userId, $mission);

                    // Skip if already completed for this period
                    if ($progress->is_completed) {
                        continue;
                    }

                    // Increment progress
                    $increment = ($mission->type === 'bet_count') ? 1 : $betAmount;
                    $progress->current_value += $increment;

                    if ($progress->current_value >= $mission->target_value) {
                        $progress->is_completed = true;
                    }

                    $progress->save();
                }

                // 3. Update User VIP Points and Coins
                $user = \App\Models\User::find($userId);
                if ($user) {
                    $pointsToAdd = intval($betAmount);
                    if ($pointsToAdd > 0) {
                        $user->increment('vip_points', $pointsToAdd);
                        $user->increment('vip_coins', $pointsToAdd);
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Error in ProgressionTracker::trackBet: ' . $e->getMessage());
        }
    }

    /**
     * Track user progress for deposits approved.
     */
    public static function trackDeposit($userId, $depositAmount)
    {
        try {
            DB::transaction(function () use ($userId, $depositAmount) {
                // Process Active Missions (deposit_amount)
                $activeMissions = Mission::where('is_active', true)
                    ->where('type', 'deposit_amount')
                    ->get();

                foreach ($activeMissions as $mission) {
                    $progress = self::getOrInitializeProgress($userId, $mission);

                    // Skip if already completed
                    if ($progress->is_completed) {
                        continue;
                    }

                    $progress->current_value += $depositAmount;

                    if ($progress->current_value >= $mission->target_value) {
                        $progress->is_completed = true;
                    }

                    $progress->save();
                }
            });
        } catch (\Exception $e) {
            Log::error('Error in ProgressionTracker::trackDeposit: ' . $e->getMessage());
        }
    }

    /**
     * Get or initialize progression record for a user and mission, resetting if period expired.
     */
    public static function getOrInitializeProgress($userId, $mission)
    {
        $now = Carbon::now();
        
        // Calculate bounds for reset_at
        $resetAt = null;
        if ($mission->period === 'daily') {
            $resetAt = Carbon::now()->endOfDay();
        } elseif ($mission->period === 'weekly') {
            $resetAt = Carbon::now()->endOfWeek();
        }

        // For daily/weekly missions, we fetch the progress matching the current active period.
        // If resetAt is null (once), we fetch the only progress record.
        $query = UserMissionProgress::where('user_id', $userId)
            ->where('mission_id', $mission->id);

        if ($resetAt !== null) {
            // For active periods, the reset_at dates of our current records should match
            // or be in the future. If we have a past record, it has expired and we need to reset/re-create.
            $progress = $query->orderBy('id', 'desc')->first();
            
            if ($progress) {
                // If the record exists but has expired, reset/renew it
                if ($progress->reset_at && $now->greaterThan($progress->reset_at)) {
                    $progress->current_value = 0.00;
                    $progress->is_completed = false;
                    $progress->reward_claimed = false;
                    $progress->reset_at = $resetAt;
                    $progress->save();
                }
                return $progress;
            }
        } else {
            $progress = $query->first();
            if ($progress) {
                return $progress;
            }
        }

        // If no progress record exists, create a new one
        return UserMissionProgress::create([
            'user_id' => $userId,
            'mission_id' => $mission->id,
            'current_value' => 0.00,
            'is_completed' => false,
            'reward_claimed' => false,
            'reset_at' => $resetAt,
        ]);
    }
}
