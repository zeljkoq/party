<?php

namespace App\Http\Controllers;

use App\Http\Services\AdminPartyService;
use App\Http\Services\AdminSongService;
use App\Http\Services\HomeService;
use App\Http\Services\LoginService;
use App\Http\Services\PartyService;
use App\Http\Services\RegisterService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function adminSongService()
    {
        return new AdminSongService;
    }

    public function adminPartyService()
    {
        return new AdminPartyService();
    }

    public function homeService()
    {
        return new HomeService();
    }

    public function partyService()
    {
        return new PartyService();
    }

    public function loginService()
    {
        return new LoginService();
    }

    public function registerService()
    {
        return new RegisterService();
    }
}
