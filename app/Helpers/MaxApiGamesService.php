<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class MaxApiGamesService
{
    private $baseUrl = 'https://maxapigames.com/api/v2';
    private $agentCode;
    private $agentToken;

    public function __construct()
    {
        $this->agentCode = Setting::get('maxapigames_agent_code');
        $this->agentToken = Setting::get('maxapigames_agent_token');
    }

    /**
     * Perform an authenticated call against the MAX API GAMES v2 endpoint.
     */
    private function call($method, array $params = [])
    {
        $payload = array_merge([
            'method' => $method,
            'agent_code' => $this->agentCode,
            'agent_token' => $this->agentToken,
        ], $params);

        try {
            $response = Http::timeout(15)->post($this->baseUrl, $payload);
        } catch (\Exception $e) {
            Log::error('MaxApiGames HTTP Error:', ['method' => $method, 'msg' => $e->getMessage()]);
            return ['status' => 0, 'msg' => 'Erro de conexão com a API'];
        }

        $data = $response->json();

        if (!$response->successful() || !is_array($data)) {
            Log::error('MaxApiGames Erro HTTP:', ['method' => $method, 'status' => $response->status(), 'body' => $response->body()]);
            return ['status' => 0, 'msg' => 'Falha na comunicação com a API'];
        }

        return $data;
    }

    /**
     * Get list of providers.
     */
    public function getProviders()
    {
        return $this->call('provider_list');
    }

    /**
     * Get list of games, optionally filtered by provider.
     */
    public function getGames($providerCode = null)
    {
        $params = [];
        if ($providerCode) {
            $params['provider_code'] = $providerCode;
        }

        $data = $this->call('game_list', $params);

        if (isset($data['status']) && (int)$data['status'] === 1 && !empty($data['games'])) {
            foreach ($data['games'] as &$game) {
                if (!empty($game['banner']) && str_starts_with($game['banner'], '/')) {
                    $game['banner'] = 'https://maxapigames.com' . $game['banner'];
                }
            }
            unset($game);
        }

        return $data;
    }

    /**
     * Launch a game.
     */
    public function gameLaunch($userCode, $gameCode, $callbackUrl = null)
    {
        $params = [
            'user_code' => (string)$userCode,
            'game_code' => $gameCode,
        ];

        if ($callbackUrl) {
            $params['callback_url'] = $callbackUrl;
        }

        Log::info('MaxApiGames Launch Request:', $params);
        $data = $this->call('game_launch', $params);
        Log::info('MaxApiGames Launch Response:', (array)$data);

        if (isset($data['status']) && (int)$data['status'] === 1 && !empty($data['launch_url'])) {
            return ['status' => true, 'game_url' => $data['launch_url']];
        }

        return ['status' => false, 'msg' => $data['msg'] ?? 'Erro ao iniciar o jogo'];
    }

    /**
     * Get agent balance.
     */
    public function moneyInfo()
    {
        return $this->call('money_info');
    }

    /**
     * Get agent settings/info.
     */
    public function agentInfo()
    {
        return $this->call('agent_info');
    }
}
