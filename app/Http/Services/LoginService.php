<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.6.18.
 * Time: 13.10
 */

namespace App\Http\Services;


class LoginService
{
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