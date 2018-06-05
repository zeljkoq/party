<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.index');
    }

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
