<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderCancelMail;
use App\User;
use Mail;
use App\Mail\OrderCancelMail as CancelMail;

class NotifyCancelCreated
{


    public function handle(OrderCancelMail $userData)
    {

        $email = $userData->userData['email'];
        Mail::to($email)->send(new CancelMail($userData->userData));
    }


}
