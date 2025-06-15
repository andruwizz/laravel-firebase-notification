<?php

namespace App\Traits;

use App\Models\PushNotificationToken;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasPushNotificationToken
{
    /**
     * Get notification token for notifiable
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pushNotificationToken(): HasOne
    {
        return $this->hasOne(PushNotificationToken::class);
    }

    /**
     * Get route for cloude messaging notification
     * 
     * @param Illuminate\Notifications\Notification|null
     * 
     * @return mixed
     */
    public function routeNotificationForCloudMessaging(?Notification $notification): mixed
    {
        return $this->pushNotificationToken?->token;
    }
}