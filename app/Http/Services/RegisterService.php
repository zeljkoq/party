<?php

namespace App\Http\Services;

use App\Models\User;

/**
 * Class RegisterService
 *
 * @package App\Http\Services
 */
class RegisterService
{
    /**
     * @param $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register($request)
    {
        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();

        $user->roles()->attach($request->userRole);

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