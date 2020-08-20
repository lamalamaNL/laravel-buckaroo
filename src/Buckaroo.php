<?php

namespace LamaLama\LaravelBuckaroo;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use LamaLama\LaravelBuckaroo\Acknowledgments\PaymentMethods;
use LamaLama\LaravelBuckaroo\Events\PaymentCompletedEvent;

class Buckaroo
{
    protected $api;

    /**
     * Buckaroo constructor.
     * @param $api
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function subscribeAndPay(Customer $customer, Subscription $subscription, Payment $payment): BuckarooResponse
    {
        // TODO: Add validation for Objects
        $customer->validateForSubscription();
        $customer->save();
        $subscription->customer_id = $customer->id;
        $subscription->save();
        $payment->customer_id = $customer->id;
        $payment->save();
        $payload = $this->getPayload($customer, $subscription, $payment);
        $result = $this->api->fetch('POST', 'json/Transaction', $payload);
        $rawResponse = $result->getRawResponse();

        $transactionKey = isset($rawResponse['Key']) ? $rawResponse['Key'] : null;
        $payment->transactionKey = $transactionKey;
        $payment->save();
        $redirectUrl = isset($rawResponse['RequiredAction']['RedirectURL']) ? $rawResponse['RequiredAction']['RedirectURL'] : null;

        return new BuckarooResponse($redirectUrl, $result->getStatus(), $rawResponse, $customer, $subscription, $payment);
    }

    public function oneTimePayment(Customer $customer, Payment $payment): BuckarooResponse
    {
        // TODO: Add validation for Objects
        $payment->validate();

        $customer->save();
        $payment->customer_id = $customer->id;
        $payment->status = 'open';
        $payment->save();
        $payload = $this->getPayload($customer, null, $payment);
        $result = $this->api->fetch('POST', 'json/Transaction', $payload);
        $rawResponse = $result->getRawResponse();

        $transactionKey = isset($rawResponse['Key']) ? $rawResponse['Key'] : null;
        $payment->transactionKey = $transactionKey;
        $payment->save();

        $redirectUrl = isset($rawResponse['RequiredAction']['RedirectURL']) ? $rawResponse['RequiredAction']['RedirectURL'] : null;

        return new BuckarooResponse($redirectUrl, $result->getStatus(), $rawResponse, $customer, null, $payment);
    }

    public function fetchPaymentMethods(string $locale = 'default') : PaymentMethods
    {
        $paymentOptions = new PaymentMethods($locale);
        if (array_key_exists('ideal', $paymentOptions->toArray())) {
            $buckarooResponse = Cache()->remember(
                'buckaroo_ideal_issuers_cache',
                config('buckaroo.cache.paymentOptionsCachePeriode'),
                function () {
                    return  $this->api->fetch('GET', 'json/Transaction/Specification/ideal');
                }
            );
            if ($buckarooResponse instanceof PaymentMethods) {
                $paymentOptions = $buckarooResponse;
            } else {
                $paymentOptions->parseIdealPaymentMethod($buckarooResponse);
            }
        }



        return $paymentOptions;
    }


    public function handleWebhook(array $rawResponse) : void
    {
        $statusCodeList = [
            1 => 'Error',
            190 => 'success',
            490 => 'Failed',
            491 => 'Validation Failure',
            492 => 'Technical Failure',
            890 => 'Cancelled',
            891 => 'Cancelled (Merchant)',
            690 => 'Rejected',
            790 => 'Pending',
            791 => 'Processing',
            792 => 'Awaiting Consumer',
        ];
        $payment = Payment::where('transactionKey', $rawResponse['Transaction']['Key'])->first();
        $statusCode = 1;
        if (isset($rawResponse['Transaction']['Status']['Code']['Code'])) {
            $statusCode = $rawResponse['Transaction']['Status']['Code']['Code'];
        }
        $payment->buckaroo_status = isset($statusCodeList[$statusCode]) ? $statusCodeList[$statusCode] : 'Unknown: ' . $rawResponse['Transaction']['Status']['Code']['Code'];
        $payment->buckaroo_webhook_data = json_encode($rawResponse);
        switch ($statusCode) {
            case 190:
                $payment->status = 'paid';

            break;
            case 1:
            case 490:
            case 491:
            case 492:
            case 890:
            case 891:
            case 690:
                $payment->status = 'failed';

            break;
            default:
                $payment->status = 'open';
        }
        $payment->save();

        $payment->load(['customer']);
        event(new PaymentCompletedEvent($payment, $payment->customer, $rawResponse));
    }


    private function getPayload(Customer $customer, ?Subscription $subscription, Payment $payment)
    {
        $params = [
          "Currency" => $payment->currency,
          "AmountDebit" => $payment->amount,
          "Invoice" => config('buckaroo.invoiceTitle'),
          "ClientIP" => [
              "Type" => 0,
              "Address" => $customer->ip,
           ],
          "Services" => [
            "ServiceList" => [
              [
                "Name" => $payment->paymentmethod,
                "Action" => "Pay",
              ],
            ],
          ],
        ];

        if (strtolower($payment->paymentmethod) === 'ideal') {
            $params['Services']['ServiceList'][0]['Parameters'] = [
                [
                    "Name" => "issuer",
                    "Value" => isset($payment->payment_issuer) ? $payment->payment_issuer : null,
                ],
            ];
        }

        if (! $subscription) {
            return $params;
        }

        switch ($customer->gender) {
            case 'male':
                $gender = 1;

                break;
            case 'female':
                $gender = 1;

                break;
            default:
                $gender = 0;

                break;
        }
        
        $params['StartRecurrent'] = true;
        $params['Services'] = [
            'ServiceList' => [
                [
                    "Name" => $payment->paymentmethod,
                    "Action" => "Pay",
                ],
                [
                    'Name' => 'Subscriptions',
                    'Action' => 'CreateCombinedSubscription',
                    'Parameters' => [
                        [
                            'Name' => 'IncludeTransaction',
                            'GroupType' => '',
                            'GroupID' => '',
                            'Value' => 'true',
                        ],
                        [
                            'Name' => 'TransactionVatPercentage',
                            'GroupType' => '',
                            'GroupID' => '',
                            'Value' => 0,
                        ],
                        [
                            'Name' => 'StartDate',
                            'GroupType' => 'AddRatePlan',
                            'GroupID' => '',
                            'Value' => Carbon::parse($subscription->startDate)->format('d-m-Y'),
                        ],
                        [
                            'Name' => 'RatePlanCode',
                            'GroupType' => 'AddRatePlan',
                            'GroupID' => '',
                            'Value' => $subscription->ratePlanCode,
                        ],
                        [
                            'Name' => 'ConfigurationCode',
                            'Value' => $subscription->configurationCode,
                        ],
                        [
                            'Name' => 'Code',
                            'GroupType' => 'Debtor',
                            'GroupID' => '',
                            'Value' => $subscription->code,
                        ],
                           [
                            'Name' => 'Email',
                            'GroupType' => 'Email',
                            'GroupID' => '',
                            'Value' => $customer->email,
                        ],
                        [
                            'Name' => 'LastName',
                            'GroupType' => 'Person',
                            'GroupID' => '',
                            'Value' => $customer->lastName,
                        ],
                        [
                            'Name' => 'Culture',
                            'GroupType' => 'Person',
                            'GroupID' => '',
                            'Value' => $customer->culture,
                        ],
                    ],
                ],
            ],
        ];

        if (strtolower($payment->paymentmethod) === 'ideal') {
            $params['Services']['ServiceList'][0]['Parameters'] = [
                [
                    "Name" => "issuer",
                    "Value" => isset($payment->payment_issuer) ? $payment->payment_issuer : null,
                ],
            ];
        }

        return $params;
    }
}
