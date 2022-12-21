<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ClientOrderCancelMail;
use App\User;
use Mail;
use App\Mail\ClientOrderCancelMail as ClientCancelMail;

class NotifyClientCancelCreated
{


    public function handle(ClientOrderCancelMail $userData)
    {

        $email = $userData->userData['client_email'];
        Mail::to($email)->send(new ClientCancelMail($userData->userData));
    }


}
