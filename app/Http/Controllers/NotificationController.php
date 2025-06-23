<?php

namespace App\Http\Controllers;

use App\Dto\NotificationDto;
use App\Http\Requests\NotificationRequest;
use App\Notifications\SimpleNotification;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * 
     * @param \App\Http\Requests\NotificationRequest
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(NotificationRequest $request): HttpResponse
    {
        $user = Auth::user();
        $message = $request->validated();

        $notification = new NotificationDto($message['title'], $message['body']);

        Notification::send($user, new SimpleNotification($notification));

        return Response::noContent();
    }
}
