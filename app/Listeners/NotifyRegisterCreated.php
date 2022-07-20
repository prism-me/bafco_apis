<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\RegisterCreate;
use Mail;
use App\Mail\RegisterVerifyMail as Verify;

class NotifyRegisterCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        dd('hello');
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
     public function handle(RegisterCreate $userData)
    {
        dd($userData);
        $email = $userData->userData['email'];
        Mail::to($email)->send(new Verify($userData->userData)); 
    }
}
