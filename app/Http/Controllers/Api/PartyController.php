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
        $songs = User::select(
            'songs.name',
            'songs.author',
            'songs.link',
            'songs.duration',
            'parties.name as party_name',
            'parties.date as party_date'
        )
            ->join('user_song', 'users.id', '=', 'user_song.user_id')
            ->join('songs', 'user_song.song_id', '=', 'songs.id')
            ->join('song_party', 'songs.id', '=', 'song_party.song_id')
            ->join('parties', 'song_party.party_id', '=', 'parties.id')
            ->where('users.id', Auth()->user()->id)
            ->get();
        return response([
            'parties' => $user->parties,
            'songs' => $songs
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
