<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Party\PartyResource;
use App\Models\Party;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
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
}
