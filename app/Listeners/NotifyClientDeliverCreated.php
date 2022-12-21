<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ClientOrderDeliverMail;
use App\User;
use Mail;
use App\Mail\ClientOrderDeliverMail as ClientDeliverMail;

class NotifyClientDeliverCreated
{


    public function handle(ClientOrderDeliverMail $userData)
    {

        $email = $userData->userData['email'];
        Mail::to($email)->send(new ClientDeliverMail($userData->userData));
    }


}
