<?php namespace LamaLama\LaravelBuckaroo\Tests;


use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LamaLama\LaravelBuckaroo\Acknowledgments\PaymentMethods;
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
}