<?php

namespace LamaLama\LaravelBuckaroo;

use Carbon\Carbon;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;

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
        $payload = $this->getTestPayload($customer, $subscription, $payment);
        // try {
        $result = $this->api->fetch('POST', 'json/Transaction', $payload);
        $rawResponse = $result->getRawResponse();
        // } catch (BuckarooApiException $e) {
        //     dd($e);
        // }
        //
        $redirectUrl = isset($rawResponse['RequiredAction']['RedirectURL']) ? $rawResponse['RequiredAction']['RedirectURL'] : null;

        $transactionKey = isset($rawResponse['Key']) ? $rawResponse['Key'] : null;
        $payment->transactionKey = $transactionKey;
        $payment->save();

        return new BuckarooResponse($redirectUrl, $result->getStatus(), $rawResponse, $customer, $subscription, $payment);
    }

    public function oneTimePayment(Customer $customer, Payment $payment): BuckarooResponse
    {
        $payload = $this->getTestPayload($customer, null, $payment);
        $result = $this->api->fetch('POST', 'json/Transaction', $payload);
        $rawResponse = $result->getRawResponse();

        $transactionKey = isset($rawResponse['Key']) ? $rawResponse['Key'] : null;
        $payment->transactionKey = $transactionKey;
        $payment->save();

        $redirectUrl = isset($rawResponse['RequiredAction']['RedirectURL']) ? $rawResponse['RequiredAction']['RedirectURL'] : null;

        return new BuckarooResponse($redirectUrl, $result->getStatus(), $rawResponse, $customer, $subscription, $payment);
    }

    public function getPaymentOptions()
    {
        $result = $this->api->fetch('GET', 'json/Transaction/Specification/ideal');

        return $result;
    }

    // TODO: handleHook
    public function handleWebhook()
    {
        // try {
        $result = $this->api->fetch('POST', 'json/Transaction');
        $rawResponse = $result->getRawResponse();
        // } catch (BuckarooApiException $e) {
        //     dd($e);
        // }
        $payment = Payment::where('transactionKey', $rawResponse['Transaction']['Key'])->first();
        $payment->status = isset($rawResponse['Transaction']['Status']['Code']['Description']) ? $rawResponse['Transaction']['Status']['Code']['Description'] : 'Pending';
        $payment->save();

        return $result;
    }


    private function getTestPayload(Customer $customer, Subscription $subscription, Payment $payment)
    {
        if (! $subscription) {
            $params = [
              "Currency" => $payment->currency,
              "AmountDebit" => $payment->amount,
              "Invoice" => "testPayment 10",
              "ClientIP" => [
                  "Type" => 0,
                  "Address" => "0.0.0.0",
               ],
              "Services" => [
                "ServiceList" => [
                  [
                    "Name" => $payment->service,
                    "Action" => "Pay",
                    "Parameters" => [
                      [
                        "Name" => "issuer",
                        "Value" => $payment->issuer,
                      ],
                    ],
                  ],
                ],
              ],
            ];

            return $params;
        }

        $params = [
            'Currency' => $payment->currency,
            'StartRecurrent' => 'true',
            'AmountDebit' => $payment->amount,
            'Invoice' => 'testsub1',
            'Services' => [
                'ServiceList' => [
                    [
                        'Name' => $payment->service,
                        'Action' => 'Pay',
                        'Parameters' => [
                            [
                                'Name' => 'issuer',
                                'Value' => $payment->issuer,
                            ],
                        ],
                    ],
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'CreateCombinedSubscription',
                        'Parameters' => [
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
                                'Name' => 'FirstName',
                                'GroupType' => 'Person',
                                'GroupID' => '',
                                'Value' => $customer->firstName,
                            ],
                            [
                                'Name' => 'LastName',
                                'GroupType' => 'Person',
                                'GroupID' => '',
                                'Value' => $customer->lastName,
                            ],
                            [
                                'Name' => 'Gender',
                                'GroupType' => 'Person',
                                'GroupID' => '',
                                'Value' => $customer->gender,
                            ],
                            [
                                'Name' => 'Culture',
                                'GroupType' => 'Person',
                                'GroupID' => '',
                                'Value' => $customer->culture,
                            ],
                            [
                                'Name' => 'BirthDate',
                                'GroupType' => 'Person',
                                'GroupID' => '',
                                'Value' => Carbon::parse($customer->birthDate)->format('d-m-Y'),
                            ],
                            [
                                'Name' => 'Street',
                                'GroupType' => 'Address',
                                'GroupID' => '',
                                'Value' => $customer->street,
                            ],
                            [
                                'Name' => 'HouseNumber',
                                'GroupType' => 'Address',
                                'GroupID' => '',
                                'Value' => $customer->houseNumber,
                            ],
                            [
                                'Name' => 'ZipCode',
                                'GroupType' => 'Address',
                                'GroupID' => '',
                                'Value' => $customer->zipcode,
                            ],

                            [
                                'Name' => 'City',
                                'GroupType' => 'Address',
                                'GroupID' => '',
                                'Value' => $customer->city,
                            ],
                            [
                                'Name' => 'Country',
                                'GroupType' => 'Address',
                                'GroupID' => '',
                                'Value' => $customer->country,
                            ],
                            [
                                'Name' => 'Email',
                                'GroupType' => 'Email',
                                'GroupID' => '',
                                'Value' => $customer->email,
                            ],
                            [
                                'Name' => 'Mobile',
                                'GroupType' => 'Phone',
                                'GroupID' => '',
                                'Value' => $customer->phone,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $params;
    }
}
