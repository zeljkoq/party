<?php

namespace App\Http\Controllers\Api;

use App\Models\Party;
use App\Http\Controllers\Controller;

class PartyController extends Controller
{
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
