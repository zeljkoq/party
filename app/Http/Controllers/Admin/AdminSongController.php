<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Class AdminSongController
 *
 * @package App\Http\Controllers\Admin
 */
class AdminSongController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.songs.index');
    }

    /**
     * @param int $song_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($song_id)
    {
        return view('admin.songs.edit', compact('song_id'));
    }
}
