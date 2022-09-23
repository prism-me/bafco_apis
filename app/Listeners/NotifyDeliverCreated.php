<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderDeliverMail;
use App\User;
use Mail;
use App\Mail\OrderDeliverMail as DeliverMail;

class NotifyDeliverCreated
{


    public function handle(OrderDeliverMail $userData)
    {

        $email = $userData->userData['email'];
        Mail::to($email)->send(new DeliverMail($userData->userData));
    }


}
