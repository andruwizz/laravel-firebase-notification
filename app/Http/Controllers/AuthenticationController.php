<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AuthenticationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * 
     * @param \App\Http\Requests\AuthenticationRequest
     * 
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(AuthenticationRequest $request): JsonResponse|HttpResponse
    {
        $credentials = ['email' => $request->validated('email'), 'password' => $request->validated('password')];
        $user = null;

        if (Auth::attempt($credentials)) {
            $user = Auth::getLastAttempted();
            $authToken = $user->createToken('api');

            return Response::json($authToken->plainTextToken);
        }

        return Response::noContent(401);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(): HttpResponse
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return Response::noContent();
    }
}
