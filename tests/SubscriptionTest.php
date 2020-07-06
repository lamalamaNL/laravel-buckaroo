<?php

namespace LamaLama\LaravelBuckaroo\Tests;

use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;

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

        $customer = $this->createCustomer();
        $sub = $this->createSubscription($customer);
        $payment = $this->createPayment($customer);

        try {
            /** @var Buckaroo $buckaroo */
            $buckaroo = $this->app->make(Buckaroo::class);
            $buckarooResponse = $buckaroo->subscribeAndPay($customer, $sub, $payment);
        } catch (\Exception $e) {
            dd($e);
        }
        /*
         * Are models stored in DB
         */
        $this->assertDatabaseHas('customers', ['email' => $customer->email]);
        $this->assertDatabaseHas('subscriptions', ['includeTransaction' => $sub->includeTransaction]);
        $this->assertDatabaseHas('subscriptions', ['includeTransaction' => $sub->includeTransaction]);
        $this->assertDatabaseHas('payments', ['amount' => $payment->amount, 'service' => $payment->service]);

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

        $buckaroo = $this->app->make(Buckaroo::class);

        $customer = $this->createCustomer();
        $sub = $this->createSubscription($customer);
        $payment = $this->createPayment($customer);

        $this->expectException(BuckarooApiException::class);
        $buckarooResponse = $buckaroo->subscribeAndPay($customer, $sub, $payment);
    }

    /** @test */
    public function it_will_update_payment_status_when_webhook_called()
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

        $successResponse = json_decode(
            file_get_contents(__DIR__ . '/api_response_mocks/webhook_success_response.json'),
            true
        );

        $customer = $this->createCustomer();
        $payment = $this->createPayment($customer, $successResponse['Transaction']['Key']);
        
        $this->assertDatabaseHas('payments', ['transactionKey' => $successResponse['Transaction']['Key'], 'status' => 'open']);

        $buckaroo = $this->app->make(Buckaroo::class);
        $buckarooResponse = $buckaroo->handleWebhook($successResponse);

        $this->assertDatabaseHas('payments', ['transactionKey' => $successResponse['Transaction']['Key'], 'status' => 'success']);
    }


    public function it_will_handle_redirect_requests_from_buckaroo_and_redirect_to_client_app_urls_by_config()
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
        // TODO: @Delano: 4 test van maken 4 alle 4 de reidrect url's van buckaroo
        // TODO: @Delano: Moeten hier voor de zekerheid de status van de order checken?
    }
}
