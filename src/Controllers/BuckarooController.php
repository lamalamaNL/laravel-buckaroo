<?php

namespace LamaLama\LaravelBuckaroo\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Customer;
use LamaLama\LaravelBuckaroo\Events\WebhookResponse;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Subscription;

class BuckarooController extends Controller
{
    public function getPaymentOptions(Request $request)
    {
        $buckaroo = $this->setupBuckaroo();
        $paymentOptions = $buckaroo->fetchPaymentMethods();

        return $paymentOptions->toArray();
    }

    public function makeSingleDonation(Request $request)
    {
        $requestAll = $request->all();
        $buckaroo = $this->setupBuckaroo();

        $customer = new Customer();
        $customer->fill($request->only('email', 'phone', 'firstName', 'lastName', 'gender', 'birthDate', 'street', 'houseNumber', 'zipcode', 'city', 'country', 'culture'));
        $customer->ip = $request->ip();
        $customer->save();

        $sub = new Subscription();
        $sub->fill($request->only('includeTransaction', 'startDate', 'ratePlanCode', 'configurationCode', 'code', 'SubscriptionGuid'));
        $sub->customer_id = $customer->id;
        $sub->save();

        $payment = new Payment();
        $payment->fill($request->only('amount', 'currency', 'status', 'service', 'issuer', 'transactionId'));
        $payment->customer_id = $customer->id;
        $payment->save();

        $paymentInfo = $buckaroo->oneTimePayment($customer, $payment);

        // TODO CHANGE TO REDIRECT RESPONSE
        dd($paymentInfo);
    }

    public function subscribe(Request $request)
    {
        $requestAll = $request->all();
        $buckaroo = $this->setupBuckaroo();

        $customer = new Customer();
        $customer->fill($request->only('email', 'phone', 'firstName', 'lastName', 'gender', 'birthDate', 'street', 'houseNumber', 'zipcode', 'city', 'country', 'culture'));
        $customer->ip = $request->ip();
        $customer->save();

        $sub = new Subscription();
        $sub->fill($request->only('includeTransaction', 'startDate', 'ratePlanCode', 'configurationCode', 'code', 'SubscriptionGuid'));
        $sub->customer_id = $customer->id;
        $sub->save();

        $payment = new Payment();
        $payment->fill($request->only('amount', 'currency', 'status', 'service', 'issuer', 'transactionId'));
        $payment->customer_id = $customer->id;
        $payment->save();
        
        $paymentInfo = $buckaroo->subscribeAndPay($customer, $sub, $payment);

        // TODO CHANGE TO REDIRECT RESPONSE
        dd($paymentInfo);
    }

    private function setupBuckaroo()
    {
        $apiClient = new ApiClient();
        $buckaroo = new Buckaroo($apiClient);

        return $buckaroo;
    }

    public function webhook(Request $request)
    {
        event(new WebhookResponse($request->all()));
    }
    

    /**
     * Redirect handlers
     */
    public function redirectSuccess(Request $request)
    {
        return ['url' => config('buckaroo.clientSuccessURL')];
    }

    public function redirectCancel(Request $request)
    {
        return ['url' => config('buckaroo.BucckarooFailedURL')];
    }

    public function redirectError(Request $request)
    {
        return ['url' => config('buckaroo.BucckarooFailedURL')];
    }

    public function redirectReject(Request $request)
    {
        return ['url' => config('buckaroo.BucckarooFailedURL')];
    }
}
