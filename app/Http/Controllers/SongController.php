<?php

namespace App\Http\Controllers;

/**
 * Class SongController
 *
 * @package App\Http\Controllers
 */
class SongController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('songs.index');
    }
}
