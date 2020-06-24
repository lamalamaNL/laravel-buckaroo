<?php

namespace LamaLama\LaravelBuckaroo\Tests;

use GuzzleHttp\Psr7\Response;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Customer;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Subscription;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    protected $mockApi = false;

    /** @test */
    public function it_will_create_succesfully_create_a_subscription_with_payment()
    {

        if ($this->mockApi) {
            $this->app->bind(ApiClient::class, function () {
                return new ApiClient([
                new Response(200, [
                'Content-Type' => 'application/json',
                ], file_get_contents(__DIR__ . '/api_response_mocks/create_and_pay_subscription_pending_791.json')),
                ]);
            });
        }


        /** @var Buckaroo $buckaroo */
        $buckaroo = $this->app->make(Buckaroo::class);
        $customerFillable = [
            'email' => 'john_smith@lamalama.nl',
            'phone' => '06-555555555',
            'firstName' => 'john',
            'lastName' => 'smith',
            'gender' => 'male',
            'birthDate' => '01-01-2000',
            'street' => 'bara straat',
            'houseNumber' => '5',
            'zipcode' => '0000AA',
            'city' => 'Amsterdam',
            'culture' => 'nl-NL',
            'country' => 'NL',
            'ip' => '0.0.0.0',
        ];
        $customer = new Customer($customerFillable);

        $subFillable = [
            'includeTransaction' => '????',
            'startDate' => new \DateTime(),
            'ratePlanCode' => 'u24atwfd',
            'configurationCode' => 'ea2pvc5w',
            'code' => 'AapjeTest',
            'SubscriptionGuid' => null,
        ];
        $sub = new Subscription($subFillable);

        $payFillable = [
            'amount' => 10,
            'currency' => 'EUR',
            'status' => 'open',
            'service' => 'ideal',
            'issuer' => 'RABO',
            'transactionId' => null,
        ];
        $payment = new Payment($payFillable);
        $buckarooResponse = $buckaroo->subscribeAndPay($customer, $sub, $payment);
        /*
         * Are models stored in DB
         */
        $this->assertDatabaseHas('customers', ['email' => $customerFillable['email']]);
        $this->assertDatabaseHas('subscriptions', ['includeTransaction' => $subFillable['includeTransaction']]);
        $this->assertDatabaseHas('subscriptions', ['includeTransaction' => $subFillable['includeTransaction']]);
        $this->assertDatabaseHas('payments', ['amount' => $payFillable['amount'], 'service' => $payFillable['service']]);

        /*
         * Check on the response
         */
        $this->assertObjectHasAttribute('redirectUrl', $buckarooResponse);
        $this->assertNotNull($buckarooResponse->getPayment()->transactionKey);
    }


    public function it_will_handle_the_webhook_and_update_internal_status()
    {
        if ($this->mockApi) {
            $this->app->bind(ApiClient::class, function () {
                return new ApiClient([
                new Response(200, [
                'Content-Type' => 'application/json',
                ], file_get_contents(__DIR__ . '/api_response_mocks/create_and_pay_subscription_success_190.json')),
                ]);
            });
        }

        $buckaroo = $this->app->make(Buckaroo::class);

        $buckarooResponse = $buckaroo->handleWebhook(json_decode(file_get_contents(__DIR__ . '/api_response_mocks/webhook_success_response.json'), true));
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
            'ip' => '0.0.0.0',
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
            'customer_id' => $customer->id,
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
        // TODO: Check for 419 status
    }


    public function fetching_payment_options()
    {
        $buckaroo = $this->app->make(Buckaroo::class);
        $buckaroo->getPaymentOptions();

        $this->assertTrue(true);
    }
}
