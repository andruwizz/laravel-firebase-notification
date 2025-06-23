<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Models\PushNotificationToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class SubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * 
     * @param \App\Http\Requests\SubscriptionRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SubscriptionRequest $request): JsonResponse
    {
        $user = Auth::user();
        $token = PushNotificationToken::query()->updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $request->validated('token')]
        );

        return Response::json($token);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(): HttpResponse
    {
        $user = Auth::user();
        PushNotificationToken::query()
            ->where('user_id', $user->id)
            ->delete();

        return Response::noContent();
    }
}
