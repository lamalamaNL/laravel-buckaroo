<?php

namespace LamaLama\LaravelBuckaroo\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LamaLama\LaravelBuckaroo\ApiClient;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Payment;

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
    public function redirectSuccess(Request $request) : RedirectResponse
    {
        return new RedirectResponse($this->getSuccessUrl($request));
    }

    /**
     * @return RedirectResponse
     */
    public function redirectCancel(Request $request) : RedirectResponse
    {
        return new RedirectResponse($this->getNoSuccessUrl($request));
    }

    /**
     * @return RedirectResponse
     */
    public function redirectError(Request $request) : RedirectResponse
    {
        return new RedirectResponse($this->getNoSuccessUrl($request));
    }

    /**
     * @return RedirectResponse
     */
    public function redirectReject(Request $request) : RedirectResponse
    {
        return new RedirectResponse($this->getNoSuccessUrl($request));
    }

    private function getSuccessUrl(Request $request) : string
    {
        $url = config('buckaroo.redirects.clientSuccessUrl');
        $transactionKey = $request->get('brq_transactions');
        if ($transactionKey) {
           $payment = Payment::query()->where('transactionKey', $transactionKey)->first();
           if ($payment->redirect_success) {
               $url = $payment->redirect_success;
           }
           $url = strpos($url, '?') === false ? "$url?amount=$payment->amount" : "$url&amount=$payment->amount";
        }

        return $url;
    }


    private function getNoSuccessUrl(Request $request) : string
    {
        $url = config('buckaroo.redirects.clientNoSuccessUrl');
        $transactionKey = $request->get('brq_transactions');
        if ($transactionKey) {
           $payment = Payment::query()->where('transactionKey', $transactionKey)->first();
           if ($payment->redirect_failed) {
               $url = $payment->redirect_failed;
           }
           $url = strpos($url, '?') === false ? "$url?amount=$payment->amount" : "$url&amount=$payment->amount";
        }

        return $url;
    }
}
