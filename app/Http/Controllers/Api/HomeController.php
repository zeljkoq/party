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
        return PartyResource::collection(Party::all());
    }
}
