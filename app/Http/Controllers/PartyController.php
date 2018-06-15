<?php

namespace App\Http\Controllers;

/**
 * Class PartyController
 *
 * @package App\Http\Controllers
 */
class PartyController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('parties.index');
    }
}
