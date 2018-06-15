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
        $response = $this->homeService()->login();

        return $response;
    }
}
