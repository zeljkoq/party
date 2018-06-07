<?php

namespace App\Http\Controllers;

class SongController extends Controller
{
    public function index()
    {
        return view('songs.index');
    }
}
