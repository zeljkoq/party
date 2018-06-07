<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login()
    {
        $credentials = request(['username', 'password']);
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
