<?php namespace LamaLama\LaravelBuckaroo\Tests;

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

    ///** @test */
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

    public function it_will_update_payment_status_when_webhook_called()
    {
        
    }


    public function it_will_handle_redirect_requests_from_buckaroo_and_redirect_to_client_app_urls_by_config()
    {
        // TODO: @Delano: 4 test van maken 4 alle 4 de reidrect url's van buckaroo
        // TODO: @Delano: Moeten hier voor de zekerheid de status van de order checken?
    }
}
