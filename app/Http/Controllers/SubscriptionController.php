<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Models\User;
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
    public function store(SubscriptionRequest $request): JsonResponse|HttpResponse
    {
        $user = Auth::user();
        if ($user instanceof User) {
            $token = $user->pushNotificationToken()->updateOrCreate(
                ['notifiable_id' => $user->id, 'notifiable_type' => User::class],
                ['token' => $request->validated('token')]
            );
            
            return Response::json($token);
        }

        return HttpResponse::noContent(400);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(): HttpResponse
    {
        $user = Auth::user();
        if ($user instanceof User) {
            $user->pushNotificationToken()->delete();
        }

        return Response::noContent();
    }
}
