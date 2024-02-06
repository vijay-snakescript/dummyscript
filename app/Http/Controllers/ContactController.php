<?php

namespace App\Http\Controllers;

use Illuminate\Mail\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\ContactRequest;
use Setting;

class ContactController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $meta = (object) __("static_pages.contact");
        \Meta::setMeta($meta);

        return view('pages.contact');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\ContactRequest $request
     * @return \Illuminate\Http\Response
     */
    public function send(ContactRequest $request)
    {
        Mail::raw(
            $request->message, function (Message $message) use ($request) {
                $message->subject($request->subject)
                    ->replyTo($request->email)
                    ->to(Config::get('mail.from.address'));
            }
        );

        return back()->with('success', trans('common.messageSent'));
    }
}
