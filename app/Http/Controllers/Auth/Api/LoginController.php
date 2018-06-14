<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Auth\Api
 */
class LoginController extends Controller
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
