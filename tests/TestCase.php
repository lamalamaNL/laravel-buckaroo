<?php

namespace LamaLama\LaravelBuckaroo\Tests;

use Illuminate\Support\Str;
use LamaLama\LaravelBuckaroo\BuckarooServiceProvider;
use LamaLama\LaravelBuckaroo\Customer;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Subscription;
use LamaLama\LaravelBuckaroo\Tests\helpers\MockData;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected $mockApi = true;

    public function setUp(): void
    {
        parent::setUp();
        $this->setBuckarooCredentailsForTesting();
        $this->withFactories(__DIR__.'/database/factories');
    }

    private function setBuckarooCredentailsForTesting()
    {
        include(__DIR__ . './../testing_credentials.php');
        config()->set('buckaroo.key', $buckaroo_api_key);
        config()->set('buckaroo.secret', $buckaroo_api_secret);
        config()->set('buckaroo.subscriptions', [
            'monthly_5' => [
                'key' => 'monthly_5', // Unique key to identity subscription
                'name' => 'Vijf euro per maand',
                'amount' => 5,
                'ratePlanCode' => $buckaroo_api_subscription_ratePlanCode, // plaza.buckaroo.nl --> configuration --> Subscriptions --> products --> Rate plan -> Rate plan code
                'configurationCode' => $buckaroo_api_subscription_configurationCode // plaza.buckaroo.nl --> configuration --> Subscriptions -> configurations -> Configuration code
            ],
            'monthly_25' => [
                'key' => 'monthly_25', // Unique key to identity subscription
                'name' => 'Vijfentwintig euro per maand',
                'amount' => 25,
                'ratePlanCode' => $buckaroo_api_subscription_ratePlanCode,
                'configurationCode' => $buckaroo_api_subscription_configurationCode
            ],
            'daily_25c' => [
                'key' => 'daily_25c', // Unique key to identity subscription
                'name' => 'Vijfentwintig euro cent per dag',
                'amount' => 0.25,
                'ratePlanCode' => $buckaroo_api_subscription_ratePlanCode,
                'configurationCode' => $buckaroo_api_subscription_configurationCode
            ]
        ]);
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
