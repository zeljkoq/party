<?php

namespace App\Http\Services;

use App\Http\Requests\CreateSongRequest;
use App\Http\Requests\UpdateSongRequest;
use App\Http\Resources\Song\AdminSongResource;
use App\Models\Song;

/**
 * Class AdminSongService
 *
 * @package App\Http\Services
 */
class AdminSongService
{
    /**
     * @param CreateSongRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CreateSongRequest $request)
    {
        try {
            $data = $request->only(['name', 'author', 'link', 'duration']);
            $data['user_id'] = Auth()->user()->id;
            $song = Song::create($data);
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
     * @param UpdateSongRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateSongRequest $request)
    {
        try {
            $song = Song::findOrFail($request->id);
            $song->update($request->only(['name', 'author', 'link', 'duration']));
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
        try {
            Song::destroy($song_id);
            return response([
                'id' => $song_id,
                'success' => 'You have been successfully deleted songs.'
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => 'Error! Please, try again.'
            ]);
        }
    }
}