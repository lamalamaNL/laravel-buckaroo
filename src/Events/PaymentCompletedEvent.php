<?php

namespace LamaLama\LaravelBuckaroo\Events;

use Illuminate\Queue\SerializesModels;
use LamaLama\LaravelBuckaroo\Customer;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Subscription;

class PaymentCompletedEvent
{
    use SerializesModels;

    /**
     * @var Payment
     */
    public $payment;
    /**
     * @var Customer
     */
    public $customer;
    /**
     * @var string
     */
    public $buckarooResponse;
    /**
     * @var Subscription
     */
    public $subscription;

    /**
     * WebhookResponse constructor.
     * @param Payment $payment
     * @param Customer $customer
     * @param string $buckarooResponse
     * @param Subscription $subscription
     */
    public function __construct(
        Payment $payment,
        Customer $customer,
        array $buckarooResponse,
        Subscription $subscription = null
    ) {
        $this->payment = $payment;
        $this->customer = $customer;
        $this->buckarooResponse = $buckarooResponse;
        $this->subscription = $subscription;
    }
}
