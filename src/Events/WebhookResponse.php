<?php

namespace LamaLama\LaravelBuckaroo\Events;

use Illuminate\Queue\SerializesModels;

class WebhookResponse
{
    use SerializesModels;

    public $webhookResponse;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($webhookResponse)
    {
        $this->webhookResponse = $webhookResponse;
    }
}
