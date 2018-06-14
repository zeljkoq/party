<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('home.index');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function sendMail(Request $request)
    {
        $data = $request->message;
        try {
            Mail::send('home.mail', ['data' => $data], function ($message) use ($request) {
                $message->from($request->email, $request->name);

                $message->to('contact@qunatox.com')
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
