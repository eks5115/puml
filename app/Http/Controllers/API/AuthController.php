<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserLoginRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Log a user in.
     *
     * @param UserLoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $token = JWTAuth::attempt($request->only('email', 'password'));
        abort_unless($token, 401, 'Invalid credentials');
        return response()->json(compact('token'));
    }

    /**
     * Log the current user out.
     *
     * @return JsonResponse
     */
    public function logout()
    {
        if ($token = JWTAuth::getToken()) {
            try {
                JWTAuth::invalidate($token);
            } catch (Exception $e) {
                Log::error($e);
            }
        }

        return response()->json();
    }
}
