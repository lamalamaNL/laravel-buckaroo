<?php

namespace LamaLama\LaravelBuckaroo\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Events\WebhookResponse;

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
        $subs = $subs->map(function ($val) {
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


    public function redirectSuccess()
    {
        return redirect(config('buckaroo.clientSuccessURL'));
    }

    public function redirectCancel()
    {
        return redirect(config('buckaroo.clientNoSuccessUrl'));
    }

    public function redirectError()
    {
        return redirect(config('buckaroo.clientNoSuccessUrl'));
    }

    public function redirectReject()
    {
        return redirect(config('buckaroo.clientNoSuccessUrl'));
    }
}
