<?php

namespace App\Http\Services;

use App\Models\Party;
use App\Models\User;

/**
 * Class PartyService
 *
 * @package App\Http\Services
 */
class PartyService
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $user = User::find(Auth()->user()->id);
        $songs = User::select(
            'songs.name',
            'songs.author',
            'songs.link',
            'songs.duration',
            'parties.name as party_name',
            'parties.date as party_date'
        )
            ->join('song_party', 'users.id', '=', 'song_party.user_id')
            ->join('songs', 'song_party.song_id', '=', 'songs.id')
            ->join('parties', 'song_party.party_id', '=', 'parties.id')
            ->where('users.id', Auth()->user()->id)
            ->get();
        return response([
            'parties' => $user->parties,
            'songs'   => $songs
        ]);
    }

    public function singUp($party_id)
    {
        try {
            $party = Party::find($party_id);
            $party->users()->attach([Auth()->user()->id]);
            return response([
                'id'      => $party->id,
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
                'id' => $party->id,
                'success' => false
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => 'Error! Please, try again.'
            ]);
        }
    }
}