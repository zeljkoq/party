<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers\Api
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $response = $this->homeService()->index();

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function routes(Request $request)
    {
        $response = $this->homeService()->routes($request);

        return $response;
    }
}
