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

    public function routes(Request $request)
    {
        $response = $this->homeService()->routes($request);

        return $response;
    }
}
