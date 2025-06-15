<?php

namespace App\Services;

use App\Dto\NotificationDto;
use Exception;
use Google\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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
        if (Cache::has('firebase-access-token')) {            
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
     * @param \App\Dto\NotificationDto
     * @param string $fcmToken
     * 
     * @return mixed
     */
    public function sendMessage(NotificationDto $notification, string $fcmToken = ''): mixed
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
            'validate_only' => $notification->debug ?? false,
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

        return $client->post($this->pushUrl, $data);
    }
}