<?php

namespace LamaLama\LaravelBuckaroo\Tests;

use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Customer;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Tests\helpers\MockData;

class PaymentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_will_create_a_new_payment()
    {
        if ($this->mockApi) {
            $this->app->bind(ApiClient::class, function () {
                return new ApiClient([
                new Response(200, [
                'Content-Type' => 'application/json',
                ], file_get_contents(__DIR__ . '/api_response_mocks/create_and_pay_success_190.json')),
                ]);
            });
        }
        $amount = 10;
        $customer = new Customer(MockData::$customerProps);
        $payment = new Payment(MockData::getPaymentData($amount));

        $buckaroo = resolve(Buckaroo::class);
        $buckResult = $buckaroo->oneTimePayment($customer, $payment);
        /*
         * Are models stored in DB
         */
        $this->assertDatabaseHas('customers', ['email' => $customer->email]);
        $this->assertDatabaseHas('payments', ['amount' => $amount, 'customer_id' => $customer->id]);
        $this->assertObjectHasAttribute('redirectUrl', $buckResult);
        $this->assertNotNull($buckResult->getPayment()->transactionKey);
    }

    /** @test */
    public function it_will_update_payment_status_when_webhook_called()
    {
        if ($this->mockApi) {
            $this->app->bind(ApiClient::class, function () {
                return new ApiClient([
                new Response(200, [
                'Content-Type' => 'application/json',
                ], file_get_contents(__DIR__ . '/api_response_mocks/create_and_pay_success_190.json')),
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

        $this->assertDatabaseHas('payments', ['transactionKey' => $successResponse['Transaction']['Key'], 'status' => 'paid']);
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
}
