<?php

namespace LamaLama\LaravelBuckaroo\Tests;

use LamaLama\LaravelBuckaroo\BuckarooServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use LamaLama\LaravelBuckaroo\Customer;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Subscription;
use LamaLama\LaravelBuckaroo\Tests\helpers\MockData;
use Illuminate\Support\Str;

abstract class TestCase extends Orchestra
{
    protected $mockApi = true;

    public function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__.'/database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            BuckarooServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Model creators
     */
    public function createCustomer(): Customer
    {
        $fillable = MockData::$customerProps;
        $customer = new Customer($fillable);
        $customer->save();
        return $customer;
    }

    public function createSubscription(Customer $customer): Subscription
    {
        $fillable = [
            'customer_id' => $customer->id,
            'includeTransaction' => '????',
            'startDate' => new \DateTime(),
            'ratePlanCode' => 'u24atwfd',
            'configurationCode' => 'ea2pvc5w',
            'code' => Str::random(24),
            'SubscriptionGuid' => null,
        ];
        return new Subscription($fillable);
    }

    public function createPayment(Customer $customer, $transactionKey = null): Payment
    {
        $fillable = MockData::getPaymentData(5);
        $fillable['customer_id'] = $customer->id;
        $fillable['transactionKey'] = $transactionKey;
        $payment = new Payment($fillable);
        $payment->save();
        return $payment;
    }
}
