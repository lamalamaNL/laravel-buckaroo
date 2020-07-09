<?php

namespace LamaLama\LaravelBuckaroo\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Customer;
use LamaLama\LaravelBuckaroo\Events\WebhookResponse;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Subscription;

class BuckarooController extends Controller
{
    public function getPaymentmethods(Request $request) : JsonResponse
    {
        $buckaroo = $this->setupBuckaroo();
        $paymentOptions = $buckaroo->fetchPaymentMethods();

        return response()->json($paymentOptions->toArray());
    }

    public function getSubscriptions() : JsonResponse
    {
        $subs = collect(config('buckaroo.subscriptions'));
        $subs = $subs->map(function($val) {
            return collect($val)->forget(['ratePlanCode', 'configurationCode']);
        });
        return response()->json($subs);
    }




    private function setupBuckaroo()
    {
        $apiClient = new ApiClient();
        $buckaroo = new Buckaroo($apiClient);

        return $buckaroo;
    }

    public function webhook(Request $request)
    {
        event(new WebhookResponse($request->all()));
    }
    

    /**
     * Redirect handlers
     */
    public function redirectSuccess(Request $request)
    {
        return ['url' => config('buckaroo.clientSuccessURL')];
    }

    public function redirectCancel(Request $request)
    {
        return ['url' => config('buckaroo.BucckarooFailedURL')];
    }

    public function redirectError(Request $request)
    {
        return ['url' => config('buckaroo.BucckarooFailedURL')];
    }

    public function redirectReject(Request $request)
    {
        return ['url' => config('buckaroo.BucckarooFailedURL')];
    }
}
