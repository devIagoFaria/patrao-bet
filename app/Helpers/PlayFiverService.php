<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class PlayFiverService
{
    private $baseUrl = 'https://api.playfivers.com'; // Adjust if needed
    private $agentToken;
    private $secretKey;

    public function __construct()
    {
        $this->agentToken = Setting::get('playfiver_agent_token');
        $this->secretKey = Setting::get('playfiver_secret_key');
    }

    /**
     * Get list of providers.
     */
    public function getProviders()
    {
        $response = Http::get("{$this->baseUrl}/api/v2/providers");
        
        if ($response->successful()) {
            return $response->json();
        }

        return ['status' => 0, 'msg' => 'Falha ao conectar com PlayFiver'];
    }

    /**
     * Get list of games.
     */
    public function getGames($providerId = null)
    {
        $url = "{$this->baseUrl}/api/v2/games";
        if ($providerId) {
            $url .= "?provider={$providerId}";
        }

        $response = Http::get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return ['status' => 0, 'msg' => 'Falha ao conectar com PlayFiver'];
    }

    /**
     * Launch a game or sportsbook.
     */
    public function gameLaunch($gameCode, $provider, $userCode, $userBalance, $isOriginal = true, $userRtp = 70, $lang = 'pt', $isSport = false)
    {
        $payload = [
            'agentToken' => $this->agentToken,
            'secretKey' => $this->secretKey,
            'user_code' => $userCode,
            'game_code' => $gameCode,
            'provider' => $provider,
            'game_original' => (bool)$isOriginal,
            'user_balance' => (float)$userBalance,
            'user_rtp' => (int)$userRtp,
            'lang' => $lang,
            'is_sport' => (bool)$isSport,
            'ebook' => (bool)$isSport
        ];

        \Illuminate\Support\Facades\Log::info('PlayFiver Launch Request:', $payload);

        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/api/v2/game_launch", $payload);
            
            // Log response regardless of success for debugging
            \Illuminate\Support\Facades\Log::info('PlayFiver Launch Response Status: ' . $response->status());
            \Illuminate\Support\Facades\Log::info('PlayFiver Launch Response Body: ' . $response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PlayFiver HTTP Error:', ['msg' => $e->getMessage()]);
            return ['status' => false, 'msg' => 'Erro de conexão com a API'];
        }

        if ($response->successful()) {
            $data = $response->json();
            
            // Log the response for debugging - IMPORTANT
            Log::info('PlayFiver Launch Full Response:', (array)$data);

            // Check for SUCCESS in status or msg
            $isSuccess = false;
            $statusVal = isset($data['status']) ? (string)$data['status'] : '';
            $msgVal = isset($data['msg']) ? (string)$data['msg'] : '';

            if ($statusVal === '1' || strtolower($statusVal) === 'true' || strtoupper($statusVal) === 'SUCCESS' || strtoupper($msgVal) === 'SUCCESS') {
                $isSuccess = true;
            }

            // Look for the game URL in various possible fields
            $gameUrl = $data['launch_url'] ?? $data['game_url'] ?? $data['url'] ?? $data['link'] ?? ($data['data']['url'] ?? ($data['data']['game_url'] ?? null));

            if ($isSuccess && $gameUrl) {
                return [
                    'status' => true,
                    'game_url' => $gameUrl
                ];
            }
            
            Log::warning('PlayFiver Launch Falhou (Status/Msg não SUCCESS):', (array)$data);

            return [
                'status' => false,
                'msg' => $data['msg'] ?? $data['message'] ?? 'Erro retornado pela API (URL não encontrada)'
            ];
        }

        Log::error('PlayFiver Launch Erro HTTP:', ['status' => $response->status(), 'body' => $response->body()]);
        return ['status' => false, 'msg' => 'Falha ao iniciar o jogo'];
    }
}
