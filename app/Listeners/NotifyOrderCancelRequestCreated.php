<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserOrderCancelRequestMail;
use App\User;
use Mail;
use App\Mail\OrderCancelRequestMail as CancelMail;

class NotifyOrderCancelRequestCreated
{


    public function handle(UserOrderCancelRequestMail $userData)
    {

        $email = $userData->userData['email'];
        Mail::to($email)->send(new CancelMail($userData->userData));
    }


}
