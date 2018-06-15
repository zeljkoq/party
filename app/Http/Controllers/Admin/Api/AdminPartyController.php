<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Requests\CreatePartyRequest;
use App\Http\Requests\UpdatePartyRequest;
use App\Http\Controllers\Controller;

/**
 * Class AdminPartyController
 *
 * @package App\Http\Controllers\Admin\Api
 */
class AdminPartyController extends Controller
{
    /**
     * @return array
     */
    public function index()
    {
        $response = $this->adminPartyService()->index();

        return $response;
    }

    /**
     * @param CreatePartyRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CreatePartyRequest $request)
    {
        $response = $this->adminPartyService()->store($request);

        return $response;
    }

    /**
     * @param int $party_id
     *
     * @return array
     */
    public function show($party_id)
    {
        $response = $this->adminPartyService()->show($party_id);

        return $response;
    }

    /**
     * @param UpdatePartyRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdatePartyRequest $request)
    {
        $response = $this->adminPartyService()->update($request);

        return $response;
    }

    /**
     * @param int $party_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete($party_id)
    {
        $response = $this->adminPartyService()->delete($party_id);

        return $response;
    }

    /**
     * @param int $party_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function start($party_id)
    {
        $response = $this->adminPartyService()->start($party_id);

        return $response;
    }

    /**
     * @param int $party_id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function details($party_id)
    {
        $response = $this->adminPartyService()->details($party_id);

        return $response;
    }
}
