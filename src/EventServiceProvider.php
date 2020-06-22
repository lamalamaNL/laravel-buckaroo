<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use LamaLama\LaravelBuckaroo\Events\WebhookResponse;
use LamaLama\LaravelBuckaroo\Listeners\HandleWebhookResponse;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WebhookResponse::class => [
            HandleWebhookResponse::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
