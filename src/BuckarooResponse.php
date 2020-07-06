<?php

namespace LamaLama\LaravelBuckaroo;

class BuckarooResponse
{

    /**
     * @var String
     */
    protected $redirectUrl;

    /**
     * @var String
     */
    protected $status;

    /**
     * @var Array
     */
    protected $rawResponse;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var Subscription
     */
    protected $subscription;

    /**
     * @var Payment
     */
    protected $payment;

    /**
     * BuckarooResponse constructor.
     * @param String $redirectUrl
     * @param Array $rawResponse
     * @param Customer $customer
     * @param Subscription $subscription
     * @param Payment $payment
     */
    public function __construct(
        String $redirectUrl,
        String $status,
        $rawResponse,
        Customer $customer,
        ?Subscription $subscription = null,
        ?Payment $payment = null
    ) {
        $this->setRedirectUrl($redirectUrl);
        $this->setStatus($status);
        $this->setRawResponse($rawResponse);
        $this->setCustomer($customer);
        $this->setSubscription($subscription);
        $this->setPayment($payment);
    }

    public function setRedirectUrl(String $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    public function getRedirectUrl(): String
    {
        return $this->redirectUrl;
    }

    public function setStatus(String $status)
    {
        $this->status = $status;
    }

    public function getStatus(): String
    {
        return $this->status;
    }

    public function setRawResponse($rawResponse)
    {
        $this->rawResponse = $rawResponse;
    }

    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setSubscription(?Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }

    public function setPayment(?Payment $payment)
    {
        $this->payment = $payment;
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }
}
