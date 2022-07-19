<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ForgetPasswordMail;
use App\User;
use Mail;

class NotifyForgetPasswordCreated
{
     public function __construct()
    {
        //
    }

  
    public function handle(ForgetPasswordMail $userData)
    {
        $email = $userData['email'];
        Mail::to($email)->send('emails.forget', $userData); 
    }
}
