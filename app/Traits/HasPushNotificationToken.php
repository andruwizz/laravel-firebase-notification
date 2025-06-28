<?php

namespace App\Traits;

use App\Models\PushNotificationToken;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasPushNotificationToken
{
    /**
     * Get notification token for notifiable
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function pushNotificationToken(): MorphOne
    {
        return $this->morphOne(PushNotificationToken::class, 'notifiable');
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