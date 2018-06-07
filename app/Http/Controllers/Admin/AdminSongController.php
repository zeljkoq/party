<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminSongController extends Controller
{
    public function index()
    {
        return view('admin.songs.index');
    }

    public function edit($song_id)
    {
        return view('admin.songs.edit', compact('song_id'));
    }
}
