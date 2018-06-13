<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Requests\CreatePartyRequest;
use App\Http\Requests\UpdatePartyRequest;
use App\Http\Resources\Party\AdminPartyResource;
use App\Http\Resources\Song\SongResource;
use App\Http\Resources\Tag\TagResource;
use App\Models\Party;
use App\Models\Song;
use App\Models\Tag;
use App\Http\Controllers\Controller;

/**
 * Class AdminPartyController
 *
 * @package App\Http\Controllers\Admin\Api
 */
class AdminPartyController extends Controller
{
    /**
     * @return array
     */
    public function index()
    {
        return [
            'data' => AdminPartyResource::collection(Party::all()),
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
            $party = new Party();
            $party->name = $request->name;
            $party->date = date('Y-m-d', strtotime($request->date));
            $party->duration = $request->duration;
            $party->capacity = $request->capacity;
            $party->description = $request->description;
            $party->cover_photo = $request->file('cover_photo')
                ->storeAs('public/cover_photos', rand(11111, 99999) . "_" . $request->cover_photo->getClientOriginalName());
            $party->cover_photo = str_replace('public/', '', $party->cover_photo);
            $party->user_id = Auth()->user()->id;
            $party->save();
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

            $lastParty = $this->getLastParty($party);

            $previousSongs = $this->getSongsFromLastParty($lastParty);

            $durationInMinutes = $request->duration * 60;

            $songs = Song::all()->toArray();
            shuffle($songs);

            $songsArr = [];
            foreach ($songs as $key => $song) {
                $repetition = $this->checkSongRepetition($songsArr, $previousSongs);

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
            $party->name = $request->name;
            $party->date = date('Y-m-d', strtotime($request->date));
            $party->duration = $request->duration;
            $party->capacity = $request->capacity;
            $party->description = $request->description;
            $party->save();
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
            $party = Party::findOrFail($party_id);
            $party->delete();
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
     *
     * @return bool
     */
    public function checkSongRepetition($songsArr, $previousSongs)
    {
        if (count($songsArr) > 0) {
            $lastSong = end($songsArr);
            if (in_array($lastSong, $previousSongs)) {
                $previousKey = array_search($lastSong, $previousSongs);
                if (isset($previousSongs[$previousKey+1]) && $previousSongs[$previousKey+1] == $song['id']) {
                    return true;
                }
                if (isset($previousSongs[$previousKey-1]) && $previousSongs[$previousKey-1] == $song['id']) {
                    return true;
                }
            }
        }
    }
}
