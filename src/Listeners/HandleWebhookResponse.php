<?php

namespace LamaLama\LaravelBuckaroo\Listeners;

use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Events\WebhookResponse;

class HandleWebhookResponse
{
    private $buckaroo;
   
    public function __construct()
    {
        $this->buckaroo = new Buckaroo(new ApiClient());
    }

    public function handle(WebhookResponse $event)
    {
        $this->buckaroo->handleWebhook($event->webhookResponse);
    }
}
