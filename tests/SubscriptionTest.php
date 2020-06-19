<?php

namespace LamaLama\LaravelBuckaroo\Tests;

use GuzzleHttp\Psr7\Response;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Customer;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Subscription;

class SubscriptionTest extends TestCase
{
    protected $mockApi = true;

    /** @test */
    public function it_will_create_succesfully_create_a_subscription_with_payment()
    {
        // TODO: Change mock response to: https://dev.buckaroo.nl/AdditionalServices/Description/subscriptions#createcombinedsubscription (response)
        if ($this->mockApi) {
            $this->app->bind(ApiClient::class, function () {
                return new ApiClient([
                new Response(200, [
                    'Content-Type' => 'application/json',
                ], file_get_contents(__DIR__ . '/api_response_mocks/test.json')),
                ]);
            });
        }

        // TODO mode fillables to json files
        $buckaroo = $this->app->make(Buckaroo::class);
        $fillable = [
            'email' => 'john_smith@lamalama.nl',
            'phone' => '06-555555555',
            'firstName' => 'john',
            'lastName' => 'smith',
            'gender' => '1',
            'birthDate' => '01-01-2000',
            'street' => 'bara straat',
            'houseNumber' => '5',
            'zipcode' => '0000AA',
            'city' => 'Amsterdam',
            'culture' => 'nl-NL',
            'country' => 'NL',
        ];
        $customer = new Customer($fillable);

        $fillable = [
            'customer_id' => $customer->id,
            'includeTransaction' => '????',
            'startDate' => new \DateTime(),
            'ratePlanCode' => 'u24atwfd',
            'configurationCode' => 'ea2pvc5w',
            'code' => 'AapjeTest',
            'SubscriptionGuid' => null,
        ];
        $sub = new Subscription($fillable);

        $fillable = [
            'subscription_id' => $sub->id,
            'amount' => 10,
            'currency' => 'EUR',
            'status' => 'open',
            'service' => 'ideal',
            'issuer' => 'RABO',
            'transactionId' => null,
        ];
        $payment = new Payment($fillable);
        $buckarooResponse = $buckaroo->subscribeAndPay($customer, $sub, $payment);
        dd($buckarooResponse);


    }

    public function it_will_handle_the_webhook_and_update_internal_status()
    {
        // TODO: Change mock response to: https://dev.buckaroo.nl/AdditionalServices/Description/subscriptions#createcombinedsubscription (push)
    }


    public function it_will_throw_a_491_error_when_using_incorrect_paramenters()
    {
        if ($this->mockApi) {
            $this->app->bind(ApiClient::class, function () {
                return new ApiClient([
                new Response(200, [
                'Content-Type' => 'application/json',
                ], file_get_contents(__DIR__ . '/api_response_mocks/create_and_pay_subscription_error_491.json')),
                ]);
            });
        }

        // TODO mode fillables to json files
        $buckaroo = $this->app->make(Buckaroo::class);
        $fillable = [
            'email' => 'john_smith@lamalama.nl',
            'phone' => '06-555555555',
            'firstName' => 'john',
            'lastName' => 'smith',
            'gender' => '1',
            'birthDate' => '01-01-2000',
            'street' => 'bara straat',
            'houseNumber' => '5',
            'zipcode' => '0000AA',
            'city' => 'Amsterdam',
            'culture' => 'nl-NL',
            'country' => 'NL',
        ];
        $customer = new Customer($fillable);

        $fillable = [
            'customer_id' => $customer->id,
            'includeTransaction' => '????',
            'startDate' => new \DateTime(),
            'ratePlanCode' => 'u24atwfd',
            'configurationCode' => 'ea2pvc5w',
            'code' => 'AapjeTest',
            'SubscriptionGuid' => null,
        ];
        $sub = new Subscription($fillable);

        $fillable = [
            'subscription_id' => $sub->id,
            'amount' => 10,
            'currency' => 'EUR',
            'status' => 'open',
            'service' => 'ideal',
            'issuer' => 'RABO',
            'transactionId' => null,
        ];
        $payment = new Payment($fillable);
        $this->expectException(BuckarooApiException::class);
        $buckarooResponse = $buckaroo->subscribeAndPay($customer, $sub, $payment);
        dd($buckarooResponse);
        // TODO: Check for 419 status
    }


    public function fetching_payment_options()
    {
        $buckaroo = $this->app->make(Buckaroo::class);
        $buckaroo->getPaymentOptions();

        $this->assertTrue(true);
    }
}
