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
        $response = $this->adminSongService()->store($request);

        return $response;
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
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateSongRequest $request)
    {
        $response = $this->adminSongService()->update($request);

        return $response;
    }

    /**
     * @param int $song_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete($song_id)
    {
        $response = $this->adminSongService()->delete($song_id);

        return $response;
    }
}
