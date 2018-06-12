<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Song\AdminSongResource;
use App\Http\Requests\CreateSongRequest;
use App\Http\Requests\UpdateSongRequest;
use App\Models\Song;

/**
 * Class AdminSongController
 *
 * @package App\Http\Controllers\Admin\Api
 */
class AdminSongController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return AdminSongResource::collection(Song::all());
    }

    /**
     * @param CreateSongRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CreateSongRequest $request)
    {
        try {
            $song = new Song();
            $song->name = $request->name;
            $song->author = $request->author;
            $song->link = $request->link;
            $song->duration = $request->duration;
            $song->user_id = Auth()->user()->id;
            $song->save();
            return response([
                'data' => new AdminSongResource($song),
                'success' => 'You have been successfully created song.'
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => 'Error! Please, try again.'
            ]);
        }
    }

    /**
     * @param int $song_id
     *
     * @return AdminSongResource
     */
    public function show($song_id)
    {
        $song = Song::findOrFail($song_id);
        return new AdminSongResource($song);
    }

    /**
     * @param UpdateSongRequest $request
     * @param int               $song_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateSongRequest $request)
    {
        try {
            $song = Song::findOrFail($request->id);
            $song->name = $request->name;
            $song->author = $request->author;
            $song->link = $request->link;
            $song->duration = $request->duration;
            $song->save();
            return response([
                'data' => new AdminSongResource($song),
                'success' => 'You have been successfully updated song.'
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => 'Error! Please, try again.'
            ]);
        }
    }

    /**
     * @param int $song_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete($song_id)
    {
//        try {
            $song = Song::findOrFail($song_id);
            $song->delete();
            return response([
                'id' => $song_id,
                'success' => 'You have been successfully deleted songs.'
            ]);
//        } catch (\Exception $e) {
//            return response([
//                'error' => 'Error! Please, try again.'
//            ]);
//        }
    }
}
