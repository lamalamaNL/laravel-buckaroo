<?php

namespace LamaLama\LaravelBuckaroo\Tests;

use GuzzleHttp\Psr7\Response;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Subscription;

class SubscriptionTest extends TestCase
{
    protected $mockApi = true;


    /** @test */
    public function it_will_throw_a_491_error_with_wrong_paramenters()
    {

        if ($this->mockApi) {
            $this->app->bind(ApiClient::class, function() {
                return new ApiClient([
                    new Response(200, [
                        'Content-Type' => 'application/json'
                    ], file_get_contents(__DIR__ . '/api_response_mocks/create_and_pay_subscription_error_491.json'))
                ]);
            });
        }

        $buckaroo = $this->app->make(Buckaroo::class);
        $sub = new Subscription(new \DateTime(), 'u24atwfd', '????');
        $payment = new Payment('ideal');
        $this->expectException(BuckarooApiException::class);
        $buckaroo->subscribeAndPay($sub, $payment);

        // TODO: Check for 419 status


    }


    public function fetching_payment_options()
    {
        $buckaroo = $this->app->make(Buckaroo::class);
        $buckaroo->getPaymentOptions();

        $this->assertTrue(true);
    }
}

