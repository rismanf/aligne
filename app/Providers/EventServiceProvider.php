<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\NewUserRegistered;
use App\Listeners\SendNewUserRegistrationMail;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewUserRegistered::class => [
            SendNewUserRegistrationMail::class,
        ],
    ];
}
