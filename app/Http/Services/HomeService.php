<?php

namespace App\Http\Services;

use App\Http\Resources\Party\PartyResource;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Class HomeService
 *
 * @package App\Http\Services
 */
class HomeService
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $parties = Party::all();
        foreach ($parties as $key => $party) {
            if ($party->date < date('Y-m-d')) {
                unset($parties[$key]);
            }
        }
        return PartyResource::collection($parties);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function routes(Request $request)
    {
        foreach ($request->user()->roles as $key => $role) {
            $roles[$key] = $role->name;
        }
        return response($roles);
    }

    /**
     * @param $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function sendMail($request)
    {
        $data = $request->message;
        try {
            Mail::send('home.mail', ['data' => $data], function ($message) use ($request) {
                $message->from($request->email, $request->name);

                $message->to('stefan.kuzmic@qunatox.com')
                    ->subject('Contact Form');
            });
            return response([
                'success' => 'Your contact form have been successfully sent.'
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => 'Error. Please, try again.'
            ]);
        }
    }
}