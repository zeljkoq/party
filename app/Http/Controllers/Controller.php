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

/**
 * Class Controller
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @return AdminSongService
     */
    public function adminSongService()
    {
        return new AdminSongService;
    }

    /**
     * @return AdminPartyService
     */
    public function adminPartyService()
    {
        return new AdminPartyService();
    }

    /**
     * @return HomeService
     */
    public function homeService()
    {
        return new HomeService();
    }

    /**
     * @return PartyService
     */
    public function partyService()
    {
        return new PartyService();
    }

    /**
     * @return LoginService
     */
    public function loginService()
    {
        return new LoginService();
    }

    /**
     * @return RegisterService
     */
    public function registerService()
    {
        return new RegisterService();
    }
}
