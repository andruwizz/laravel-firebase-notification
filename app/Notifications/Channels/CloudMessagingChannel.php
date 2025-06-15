<?php

namespace App\Notifications\Channels;

use App\Services\CloudMessagingService;
use Illuminate\Notifications\Notification;

class CloudMessagingChannel
{
    public function __construct(protected CloudMessagingService $cloudMessaging)
    {
        //
    }

    public function send(object $notifiable, Notification $notification): void
    {
        $token = $notifiable->routeNotificationFor('CloudMessaging', $notification);
        $message = $notification->toCloudMessaging($notifiable);
        
        $this->cloudMessaging->sendMessage($message, $token);
    }
}