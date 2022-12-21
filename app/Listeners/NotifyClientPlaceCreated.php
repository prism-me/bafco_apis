<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ClientOrderPlaceMail;
use App\User;
use Mail;
use App\Mail\ClientOrderPlaceMail as ClientPlaceMail;

class NotifyClientPlaceCreated
{
    public function handle(ClientOrderPlaceMail $userData)
    {
        $email = $userData->userData['client_email'];
        Mail::to($email)->send(new ClientPlaceMail($userData->userData));
    }
}