<?php

namespace LamaLama\LaravelBuckaroo\Tests;

use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Customer;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Subscription;
use LamaLama\LaravelBuckaroo\Tests\helpers\MockData;

class SubscriptionTest extends TestCase
{
    use DatabaseMigrations;

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
        $customerFillable = MockData::$customerProps;
        $customer = new Customer($customerFillable);

        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Amsterdam'));

        $subFillable = [
            'includeTransaction' => false,
            'startDate' => $date,
            'ratePlanCode' => 'u24atwfd',
            //'ratePlanCode' => 'donation_eur5_monthly',
            'configurationCode' => 'ea2pvc5w',
            'code' => Str::random(24),
            'SubscriptionGuid' => null,
        ];
        $sub = new Subscription($subFillable);

        $payFillable = MockData::getPaymentData(5);
        $payment = new Payment($payFillable);

        try {
            $buckarooResponse = $buckaroo->subscribeAndPay($customer, $sub, $payment);
        } catch (\Exception $e) {
            dd($e);
        }
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
            'email' => 'test@lamalama.nl',
            'phone' => '06555555555',
            'firstName' => 'Test',
            'lastName' => 'Testerma',
            'gender' => '1',
            'birthDate' => '01-01-1981',
            'street' => 'RJH Foruynstraat',
            'houseNumber' => '111',
            'zipcode' => '1019WK',
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

    public function it_will_update_payment_status_when_webhook_called()
    {
    }


    public function it_will_handle_redirect_requests_from_buckaroo_and_redirect_to_client_app_urls_by_config()
    {
        // TODO: @Delano: 4 test van maken 4 alle 4 de reidrect url's van buckaroo
        // TODO: @Delano: Moeten hier voor de zekerheid de status van de order checken?
    }
}
