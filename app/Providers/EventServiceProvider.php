<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\NotifyForgetPasswordCreated;
use App\Events\ForgetPasswordMail;
use App\Listeners\NotifyRegisterCreated;
use App\Events\RegisterMail;

use App\Listeners\NotifyCancelCreated;
use App\Events\OrderCancelMail;
use App\Listeners\NotifyPlaceCreated;
use App\Events\OrderPlaceMail;
use App\Listeners\NotifyDeliverCreated;
use App\Events\OrderDeliverMail;

use App\Listeners\NotifyClientCancelCreated;
use App\Events\ClientOrderCancelMail;
use App\Listeners\NotifyClientPlaceCreated;
use App\Events\ClientOrderPlaceMail;
use App\Listeners\NotifyClientDeliverCreated;
use App\Events\ClientOrderDeliverMail;

use App\Listeners\NotifyOrderCancelRequestCreated;
use App\Events\UserOrderCancelRequestMail;






class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ForgetPasswordMail::class => [
            NotifyForgetPasswordCreated::class,
        ],
        RegisterMail::class => [
            NotifyRegisterCreated::class,
        ],
        OrderPlaceMail::class => [
            NotifyPlaceCreated::class,
        ],
        OrderCancelMail::class => [
            NotifyCancelCreated::class,
        ],
        OrderDeliverMail::class => [
            NotifyDeliverCreated::class,
        ],
        ClientOrderPlaceMail::class => [
            NotifyClientPlaceCreated::class,
        ],
        ClientOrderCancelMail::class => [
            NotifyClientCancelCreated::class,
        ],
        ClientOrderDeliverMail::class => [
            NotifyClientDeliverCreated::class,
        ],
        UserOrderCancelRequestMail::class => [
            NotifyOrderCancelRequestCreated::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
