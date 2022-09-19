<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderPlaceMail;
use App\User;
use Mail;
use App\Mail\OrderPlaceMail as PlaceMail;

class NotifyPlaceCreated
{
    public function handle(OrderPlaceMail $userData)
    {
        $email = $userData->userData['email'];
        Mail::to($email)->send(new PlaceMail($userData->userData));
    }




}
