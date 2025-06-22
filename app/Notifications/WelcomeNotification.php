<?php

namespace App\Notifications;

use App\Notifications\Channels\CloudMessagingChannel;
use App\Notifications\Messages\CloudMessagingMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected object $notification)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     * 
     * @param object $notifiable
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [CloudMessagingChannel::class, 'database'];
    }

    /**
     * Get the notification's database type.
     * 
     * @param object $notifiable
     * 
     * @return string
     */
    public function databaseType(object $notifiable): string
    {
        return 'Information';
    }

    /**
     * Get the array representation of the notification.
     * 
     * @param object $notifiable
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->notification->title,
            'body' => $this->notification->body,
            'data' => $this->notification->data
        ];
    }

    /**
     * Get the cloud messaging representation of the notification.
     * 
     * @param object $notifiable
     * 
     * @return \App\Notifications\Messages\CloudMessagingMessage
     */
    public function toCloudMessaging(object $notifiable): CloudMessagingMessage
    {
        return (new CloudMessagingMessage)
            ->title($this->notification->title)
            ->body($this->notification->body)
            ->data($this->notification->data);
    }
}
