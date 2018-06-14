<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Class AdminPartyController
 *
 * @package App\Http\Controllers\Admin
 */
class AdminPartyController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.parties.index');
    }

    /**
     * @param int $party_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($party_id)
    {
        return view('admin.parties.edit', compact('party_id'));
    }
}
