<?php

namespace App\Services;

use App\Notifications\Messages\CloudMessagingMessage;
use Exception;
use Google\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudMessagingService
{
    public function __construct(
        public ?string $appConfigPath,
        public ?string $appScope,
        public ?string $pushUrl
    ) {
        $this->appConfigPath = $appConfigPath ?? config('firebase.app_config');
        $this->appScope = $appScope ?? config('firebase.app_scope');
        $this->pushUrl = $pushUrl ?? config('firebase.push_url');
    }

    /**
     * Validate app config
     * 
     * @return void
     * 
     * @throws Exception
     */
    private function validateConfig(): void
    {
        $configPath = base_path($this->appConfigPath);
        if (! @file_get_contents($configPath)) {
            throw new Exception('Config file not found in : ' . $configPath);
        }

        if (empty($this->appScope)) {
            throw new Exception('App Scope not set.');
        }

        if (empty($this->pushUrl)) {
            throw new Exception('Push Notification url not set.');
        }
    }

    /**
     * Get Access Token
     * 
     * @return string
     */
    private function getAccessToken(): string
    {
        if (! Cache::has('firebase-access-token')) {            
            $client = new Client();
            $client->setAuthConfig(base_path($this->appConfigPath));
            $client->addScope($this->appScope);
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();

            Cache::set('firebase-access-token', $token['access_token'] ?? '', $token['expires_in'] ?? 0);
            return $token['access_token'] ?? '';
        }

        return Cache::get('firebase-access-token');
    }

    /**
     * Send Push Notification
     * 
     * @param \App\Notifications\Messages\CloudMessagingMessage $notification
     * @param string $fcmToken
     * @param bool $debug
     * 
     * @return bool
     */
    public function sendMessage(CloudMessagingMessage $notification, string $fcmToken = '', bool $debug = false): bool
    {
        $this->validateConfig();

        $authToken = $this->getAccessToken();
        if (empty($authToken)) {
            throw new Exception('Firebase service auth failed.');
        }

        if (empty($fcmToken)) {
            throw new Exception('Firebase notification token empty.');
        }

        $data = [
            'validate_only' => $debug,
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $notification->title,
                    'body' => $notification->body,
                ],
                'data' => $notification->data ?? null
            ]
        ];

        $client = Http::withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', 'application/json')
            ->withToken($authToken);

        $response = $client->post($this->pushUrl, $data);
        if ($response->successful()) {
            Log::info('Send Push Notification Successful.', $response->json());
            return true;
        }

        Log::warning('Send Push Notification Failed.', $response->json());
        return false;
    }
}