<?php

namespace LamaLama\LaravelBuckaroo\Tests;

use GuzzleHttp\Psr7\Response;
use LamaLama\LaravelBuckaroo\Acknowledgments\PaymentMethods;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use Illuminate\Support\Facades\Cache;
use Closure;

class PaymentMethodsTest extends TestCase
{
    public function it_will_get_payment_methods_from_config()
    {
        $paymentMethods = new PaymentMethods();
        $paymentMethodsConfig = config('buckaroo.paymentMethods');
        foreach ($paymentMethodsConfig as $paymentMethod) {
            $this->assertArrayHasKey($paymentMethod, $paymentMethods->toArray());
        }
    }
    
    public function it_will_fetch_ideal_issuers_and_add_them_to__the_payment_methods()
    {
        if ($this->mockApi) {
            $this->app->bind(ApiClient::class, function () {
                return new ApiClient([
                new Response(200, [
                'Content-Type' => 'application/json',
                ], file_get_contents(__DIR__ . '/api_response_mocks/ideal_payment_method_response_200.json')),
                ]);
            });
        }

        $buckaroo = $this->app->make(Buckaroo::class);
        $paymentMethods = $buckaroo->fetchPaymentMethods();
        $this->assertArrayHasKey('ideal', $paymentMethods->toArray());
        $this->assertArrayHasKey('options', $paymentMethods->toArray()['ideal']);
        $this->assertArrayHasKey('issuers', $paymentMethods->toArray()['ideal']['options']);
        $this->assertGreaterThan(0, count($paymentMethods->toArray()['ideal']['options']));
    }

    /** @test */
    public function it_will_use_cache_for_payment_options_requests()
    {
        if ($this->mockApi) {
            $this->app->bind(ApiClient::class, function () {
                return new ApiClient([
                new Response(200, [
                'Content-Type' => 'application/json',
                ], file_get_contents(__DIR__ . '/api_response_mocks/ideal_payment_method_response_200.json')),
                ]);
            });
        }

        $buckaroo = $this->app->make(Buckaroo::class);
        $paymentMethods = $buckaroo->fetchPaymentMethods();
    /** @test */
        Cache::shouldReceive('remember')->once()
        ->with(
            'buckaroo_ideal_issuers_cache',
            config('buckaroo.cache.paymentOptionsCachePeriode'),
            Closure::class
        )
        ->andReturn($paymentMethods);
    }
}
