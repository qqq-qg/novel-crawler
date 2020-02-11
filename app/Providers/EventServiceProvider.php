<?php

namespace App\Providers;

use App\Events\BooksChangeSourceEvent;
use App\Events\BooksUpdateEvent;
use App\Listeners\BooksChangeSourceListener;
use App\Listeners\BooksUpdateListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class,],
        //换源
        BooksChangeSourceEvent::class => [BooksChangeSourceListener::class,],
        //更新
        BooksUpdateEvent::class => [BooksUpdateListener::class,],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
