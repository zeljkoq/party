<?php

namespace App\Http\Controllers\Api;

use App\Models\Party;
use App\Http\Controllers\Controller;
use App\Models\User;

class PartyController extends Controller
{
    public function index()
    {
        $user = User::find(Auth()->user()->id);
        dd($user->songs[0]->pivot->party_id);
        return response([
            'parties' => $user->parties,
            'songs' => $user->songs
        ]);
    }

    /**
     * @param int $party_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function singUp($party_id)
    {
        try {
            $party = Party::find($party_id);
            $party->users()->attach([Auth()->user()->id]);
            return response([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => 'Error! Please, try again.'
            ]);
        }
    }

    /**
     * @param int $party_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function singOut($party_id)
    {
        try {
            $party = Party::find($party_id);
            $party->users()->detach([Auth()->user()->id]);
            return response([
                'success' => false
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => 'Error! Please, try again.'
            ]);
        }
    }
}
