<?php

namespace LamaLama\LaravelBuckaroo\Tests;

use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Validation\ValidationException;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;
use LamaLama\LaravelBuckaroo\Subscription;

class SubscriptionTest extends TestCase
{
    use DatabaseMigrations;

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
        $sub = Subscription::createByConfigKey('montly_5', $customer);
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
        $sub = Subscription::createByConfigKey('montly_5', $customer);
        $payment = $this->createPayment($customer);

        $this->expectException(BuckarooApiException::class);
        $buckarooResponse = $buckaroo->subscribeAndPay($customer, $sub, $payment);
    }

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
        $customer = $this->createCustomer();
        $sub = Subscription::createByConfigKey('montly_5', $customer);
        $payment = $this->createPayment($customer);
        
        $buckaroo = $this->app->make(Buckaroo::class);
        $buckarooResponse = $buckaroo->subscribeAndPay($customer, $sub, $payment);

        $successResponse = $buckarooResponse->getRawResponse();

        $payment->transactionKey = $successResponse['Transaction']['Key'];
        $payment->save();
        $this->assertDatabaseHas('payments', ['transactionKey' => $successResponse['Transaction']['Key'], 'status' => 'open']);
        $this->assertDatabaseMissing('payments', ['transactionKey' => $successResponse['Transaction']['Key'], 'status' => 'success']);

        $buckarooResponse = $buckaroo->handleWebhook($successResponse);

        $this->assertDatabaseMissing('payments', ['transactionKey' => $successResponse['Transaction']['Key'], 'status' => 'open']);
        $this->assertDatabaseHas('payments', ['transactionKey' => $successResponse['Transaction']['Key'], 'status' => 'success']);
    }

    /** @test */
    public function it_will_throw_a_validation_error_if_key_is_not_configured()
    {
        $customer = $this->createCustomer();
        $this->expectException(ValidationException::class);
        $sub = Subscription::createByConfigKey('doesnotexists', $customer);
    }

    /** @test */
    public function it_will_handle_redirect_requests_from_buckaroo_and_redirect_to_client_app_urls_by_config()
    {
        $response = $this->post('/api/buckaroo/success');
        $response->assertStatus(302);
        $response->assertRedirect(config('buckaroo.redirects.clientSuccessUrl'));


        $response = $this->post('/api/buckaroo/cancel');
        $response->assertStatus(302);
        $response->assertRedirect(config('buckaroo.redirects.clientNoSuccessUrl'));

        $response = $this->post('/api/buckaroo/error');
        $response->assertStatus(302);
        $response->assertRedirect(config('buckaroo.redirects.clientNoSuccessUrl'));

        $response = $this->post('/api/buckaroo/reject');
        $response->assertStatus(302);
        $response->assertRedirect(config('buckaroo.redirects.clientNoSuccessUrl'));
    }


    /** @test */
    public function it_will_validate_the_objects()
    {
        $customer = $this->createCustomer();
        $customer->email = null;
        $this->expectException(ValidationException::class);
        $customer->validateForSubscription();
    }

}
