<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Mail\NewRegisterPICMail;
use App\Models\ManageMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendNewUserRegistrationMail
{
    use InteractsWithQueue;

    public function handle(NewUserRegistered $event): void
    {
        $listMailPIC = ManageMail::where('type', $event->typeMail)->pluck('email')->toArray();
        Log::info("START  Sending email to PIC for type: " . $event->typeMail);
        Mail::to($listMailPIC)->send(new NewRegisterPICMail($event->userData));
    }

    public function failed(NewUserRegistered $event, Throwable $exception): void
    {
        Log::info("Sending email to PIC ERROR");
    }
}
