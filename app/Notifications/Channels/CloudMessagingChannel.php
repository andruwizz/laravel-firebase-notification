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

    /**
     * Send message using service
     * 
     * @param object $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $token = $notifiable->routeNotificationFor('CloudMessaging', $notification);
        $message = $notification->toCloudMessaging($notifiable);
        
        $this->cloudMessaging->sendMessage($message, $token);
    }
}