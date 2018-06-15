<?php

namespace App\Http\Controllers;

use App\Http\Resources\Party\PartyResource;
use App\Models\Party;
use Illuminate\Http\Request;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('home.index');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function sendMail(Request $request)
    {
        $response = $this->homeService()->sendMail($request);

        return $response;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function parties()
    {
        $parties = Party::all();
        foreach ($parties as $key => $party) {
            if ($party->date < date('Y-m-d')) {
                unset($parties[$key]);
            }
        }
        return PartyResource::collection($parties);
    }
}
