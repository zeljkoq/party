<?php

namespace App\Http\Services;

use App\Http\Requests\CreatePartyRequest;
use App\Http\Requests\UpdatePartyRequest;
use App\Http\Resources\Party\AdminPartyResource;
use App\Http\Resources\Tag\TagResource;
use App\Models\Party;
use App\Models\Song;
use App\Models\Tag;
use Illuminate\Http\Request;

/**
 * Class AdminPartyService
 *
 * @package App\Http\Services
 */
class AdminPartyService
{
    /**
     * @return array
     */
    public function index()
    {
        $data = AdminPartyResource::collection(Party::where('user_id', Auth()->user()->id)->get());
        if (Auth()->user()->isAdmin()) {
            $data = AdminPartyResource::collection(Party::all());
        }
        return [
            'data' => $data,
            'tags' => TagResource::collection(Tag::all())
        ];
    }

    /**
     * @param CreatePartyRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CreatePartyRequest $request)
    {
        try {
            $data = $request->only(['name', 'duration', 'capacity', 'description']);
            $data['date'] = date('Y-m-d', strtotime($request->date));
            $data['cover_photo'] = $request->file('cover_photo')
                ->storeAs('public/cover_photos', uniqid(null, true) .
                    "_" . $request->cover_photo->getClientOriginalName());
            $data['cover_photo'] = str_replace('public/', '', $data['cover_photo']);
            $data['user_id'] = Auth()->user()->id;
            $party = Party::create($data);

            $this->attachTags($request, $party);

            $this->attachSongs($request, $party);

            return response([
                'data' => new AdminPartyResource($party),
                'success' => 'You have been successfully created party.'
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
     * @return array
     */
    public function show($party_id)
    {
        return [
            'data' => new AdminPartyResource(Party::findOrFail($party_id)),
            'tags' => TagResource::collection(Tag::all())
        ];
    }

    /**
     * @param UpdatePartyRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdatePartyRequest $request)
    {
        try {
            $party = Party::findOrFail($request->id);
            $data = $request->only(['name', 'duration', 'capacity', 'description']);
            $data['date'] = date('Y-m-d', strtotime($request->date));
            $party->update($data);
            return response([
                'data' => new AdminPartyResource($party),
                'success' => 'You have been successfully updated party.'
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
    public function delete($party_id)
    {
        try {
            Party::destroy($party_id);
            return response([
                'id' => $party_id,
                'success' => 'You have been successfully deleted party.'
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
    public function start($party_id)
    {
        try {
            $oldArtists = $this->getOldArtist(Party::where('start', 1)->get());

            $party = Party::find($party_id);
            $party->start = 1;
            $party->save();

            $registeredUsers = $this->getIdsOfRegisteredUsers($party);

            foreach ($party->songs as $song) {
                $assigned = false;
                foreach ($registeredUsers as $key => $user) {
                    if ((isset($oldArtists[$song->id]) && !in_array($user, $oldArtists[$song->id]))
                        || empty($oldArtists)
                    ) {
                        $party->songs()->updateExistingPivot($song->id, ['user_id' => $user]);
                        unset($registeredUsers[$key]);
                        $assigned = true;
                    }
                }
                if (!$assigned) {
                    $party->songs()->updateExistingPivot($song->id, ['user_id' => 1]);
                }
            }
            return response([
                'success' => 'You have been successfully started party.'
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
    public function details($party_id)
    {
        $songs = Party::findOrFail($party_id)->songs;
        return response($songs);
    }

    /**
     * @param Request $request
     * @param object $party
     */
    public function attachTags($request, $party)
    {
        $tags = $this->createTagsIfNotExists($request->tags);
        foreach ($tags as $key => $tag) {
            if (!is_numeric($tag)) {
                $newTag = new Tag;
                $newTag->name = $tag;
                $newTag->save();
                $tags[$key] = $newTag->id;
            }
        }
        $party->tags()->attach($tags);
    }

    public function attachSongs($request, $party)
    {
        $lastParty = $this->getLastParty($party);

        $previousSongs = $this->getSongsFromLastParty($lastParty);

        $durationInMinutes = $request->duration * 60;

        $songs = Song::all()->toArray();
        shuffle($songs);

        $songsArr = [];
        foreach ($songs as $key => $song) {
            $repetition = $this->checkSongRepetition($songsArr, $previousSongs, $song);

            if ($repetition) {
                continue;
            }

            if ($song['duration'] > $durationInMinutes) {
                $songsArr[$key] = $song['id'];
                break;
            }
            $songsArr[$key] = $song['id'];
            $durationInMinutes -= $song['duration'];
        }

        if ($durationInMinutes > 0) {
            foreach ($songs as $key => $song) {
                if ($song['duration'] > $durationInMinutes) {
                    $songsArr[$key] = $song['id'];
                    break;
                }
                $songsArr[$key] = $song['id'];
                $durationInMinutes -= $song['duration'];
            }
        }

        $party->songs()->attach($songsArr);
    }

    /**
     * @param array $tags
     *
     * @return array
     */
    public function createTagsIfNotExists($tags)
    {
        if (!$tags) {
            return [];
        }
        foreach ($tags as $key => $tag) {
            if (!is_numeric($tag)) {
                $newTag = new Tag;
                $newTag->name = $tag;
                $newTag->save();
                $tags[$key] = $newTag->id;
            }
        }
        return $tags;
    }

    /**
     * @param object $party
     *
     * @return null|object
     */
    public function getLastParty($party)
    {
        $pastParties = Party::where('date', '<', $party->date)->get();
        $lastParty = null;
        foreach ($pastParties as $pastParty) {
            if ($lastParty == null) {
                $lastParty = $pastParty;
                continue;
            }

            if ($pastParty->date < $lastParty->date) {
                continue;
            }

            if ($pastParty->date == $lastParty->date) {
                if ($pastParty->id < $lastParty->id) {
                    continue;
                }
            }
            $lastParty = $pastParty;
        }
        return $lastParty;
    }

    /**
     * @param object $lastParty
     *
     * @return array
     */
    public function getSongsFromLastParty($lastParty)
    {
        $previousSongs = [];
        if ($lastParty) {
            foreach ($lastParty->songs as $key => $song) {
                $previousSongs[$key] = $song->id;
            }
        }
        return $previousSongs;
    }

    /**
     * @param array $songsArr
     * @param array $previousSongs
     * @param array $song
     *
     * @return bool
     */
    public function checkSongRepetition($songsArr, $previousSongs, $song)
    {
        if (count($songsArr) <= 0) {
            return false;
        }

        $lastSong = end($songsArr);
        if (!in_array($lastSong, $previousSongs)) {
            return false;
        }

        $previousKey = array_search($lastSong, $previousSongs);
        if (isset($previousSongs[$previousKey + 1]) && $previousSongs[$previousKey + 1] == $song['id']) {
            return true;
        }
        if (isset($previousSongs[$previousKey - 1]) && $previousSongs[$previousKey - 1] == $song['id']) {
            return true;
        }
    }

    /**
     * @param array $lastParties
     *
     * @return array
     */
    public function getOldArtist($lastParties)
    {
        $oldArtists = [];
        if (!$lastParties) {
            return $oldArtists;
        }
        foreach ($lastParties as $lastParty) {
            foreach ($lastParty->songs as $song) {
                $oldArtists[$song->id][] = $song->pivot->user_id;
            }
        }
        return $oldArtists;
    }

    /**
     * @param object $party
     *
     * @return array
     */
    public function getIdsOfRegisteredUsers($party)
    {
        $registeredUsers = [];
        foreach ($party->users as $k => $user) {
            $registeredUsers[$k] = $user->id;
        }
        return $registeredUsers;
    }
}