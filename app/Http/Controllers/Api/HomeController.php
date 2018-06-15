<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Party\PartyResource;
use App\Models\Party;
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
        $parties = Party::all();
        foreach ($parties as $key => $party) {
            if ($party->date < date('Y-m-d')) {
                unset($parties[$key]);
            }
        }
        return PartyResource::collection($parties);
    }

    public function routes(Request $request)
    {
        foreach ($request->user()->roles as $key => $role) {
            $roles[$key] = $role->name;
        }
        return response($roles);
    }
}
