<?php

namespace LamaLama\LaravelBuckaroo\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;

class BuckarooController extends Controller
{
    public function getPaymentmethods(Request $request) : JsonResponse
    {
        $buckaroo = $this->setupBuckaroo();
        $locale = $request->get('locale', 'default');
        $paymentOptions = $buckaroo->fetchPaymentMethods($locale);

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

    public function webhook(Request $request,  Buckaroo $buckaroo)
    {
        $buckaroo->handleWebhook($request->all());
    }


    /**
     * @return RedirectResponse
     */
    public function redirectSuccess() : RedirectResponse
    {
        return new RedirectResponse(config('buckaroo.redirects.clientSuccessUrl'));
    }

    /**
     * @return RedirectResponse
     */
    public function redirectCancel() : RedirectResponse
    {
        return new RedirectResponse(config('buckaroo.redirects.clientNoSuccessUrl'));
    }

    /**
     * @return RedirectResponse
     */
    public function redirectError() : RedirectResponse
    {
        return new RedirectResponse(config('buckaroo.redirects.clientNoSuccessUrl'));
    }

    /**
     * @return RedirectResponse
     */
    public function redirectReject() : RedirectResponse
    {
        return new RedirectResponse(config('buckaroo.redirects.clientNoSuccessUrl'));
    }
}
