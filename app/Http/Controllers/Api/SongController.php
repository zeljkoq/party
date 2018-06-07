<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Song\SongResource;
use App\Models\Song;

/**
 * Class SongController
 *
 * @package App\Http\Controllers\Api
 */
class SongController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return SongResource::collection(Song::orderByDesc('created_at')
            ->paginate(5));
    }
}
