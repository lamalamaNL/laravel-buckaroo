<?php

namespace LamaLama\LaravelBuckaroo;

use Carbon\Carbon;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Subscription;
use LamaLama\LaravelBuckaroo\Customer;
use LamaLama\LaravelBuckaroo\BuckarooResponse;

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
        // } catch (BuckarooApiException $e) {
           // dd($e);
        // }
        $result = ['status' => 200, 'redirectUrl' => 'www.wwwdotcom.com'];

        return new BuckarooResponse($result['redirectUrl'], $result['status'], $result, $customer, $subscription, $payment);
    }

    public function getPaymentOptions()
    {
        $result = $this->api->fetch('GET', 'json/Transaction/Specification/ideal');

        return $result;
    }


    private function getTestPayload(Customer $customer, Subscription $subscription, Payment $payment)
    {
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
