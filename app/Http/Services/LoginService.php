<?php

namespace App\Http\Services;

/**
 * Class LoginService
 *
 * @package App\Http\Services
 */
class LoginService
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'access_token' => $token
        ]);
    }
}