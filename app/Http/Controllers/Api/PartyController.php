<?php

namespace App\Http\Controllers\Api;

use App\Models\Party;
use App\Http\Controllers\Controller;
use App\Models\User;

class PartyController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $response = $this->partyService()->index();

        return $response;
    }

    /**
     * @param int $party_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function singUp($party_id)
    {
        $response = $this->partyService()->singUp($party_id);

        return $response;
    }

    /**
     * @param int $party_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function singOut($party_id)
    {
        $response = $this->partyService()->singOut($party_id);

        return $response;
    }
}
