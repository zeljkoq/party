<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;

/**
 * Class RegisterController
 *
 * @package App\Http\Controllers\Auth\Api
 */
class RegisterController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getRoles()
    {
        return RoleResource::collection(Role::all());
    }

    /**
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $response = $this->registerService()->register($request);

        return $response;
    }
}
