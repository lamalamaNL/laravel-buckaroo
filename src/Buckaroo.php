<?php namespace LamaLama\LaravelBuckaroo;


use Carbon\Carbon;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;

class Buckaroo
{
    protected $api;

    /**
     * Buckaroo constructor.
     * @param $api
     */
    public function __construct(ApiClient $api) {
        $this->api = $api;
    }


    public function subscribeAndPay(Subscription $subscription, Payment $payment)
    {
        $payload = $this->getTestPayload();
        //try {
            $result = $this->api->fetch('POST', 'json/Transaction', $payload);
        //} catch (BuckarooApiException $e) {
        //    dd($e);
        //}
    }

    public function getPaymentOptions()
    {
        $result = $this->api->fetch('GET', 'json/Transaction/Specification/ideal');

        return $result;
    }


    private function getTestPayload()
    {
        $params = [
            'Currency' => 'EUR',
            'StartRecurrent' => 'true',
            'AmountDebit' => 10,
            'Invoice' => 'testsub1',
            'Services' => [
                'ServiceList' => [
                    [
                        'Name' => 'ideal',
                        'Action' => 'Pay',
                        'Parameters' => [
                            [
                                'Name' => 'issuer',
                                'Value' => 'ABNANL2A',
                            ]
                        ]
                    ],
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'CreateCombinedSubscription',
                        'Parameters' =>[
                            [
                                'Name' => 'StartDate',
                                'GroupType' => 'AddRatePlan',
                                'GroupID' => '',
                                //'Value' => '03-08-2017',
                                'Value' => Carbon::now()->format('d-m-Y'),
                            ],
                            [
                                'Name' => 'RatePlanCode',
                                'GroupType' => 'AddRatePlan',
                                'GroupID' => '',
                                'Value' => 'u24atwfd',
                            ],
                            [
                                'Name' => 'ConfigurationCode',
                                'Value' => 'ea2pvc5w',
                            ],
                            [
                                'Name' => 'Code',
                                'GroupType' => 'Debtor',
                                'GroupID' => '',
                                'Value' => 'AapjeTest',
                            ],
                            [
                                'Name' => 'FirstName',
                                'GroupType' => 'Person',
                                'GroupID' => '',
                                'Value' => 'Aapje',
                            ],
                            [
                                'Name' => 'LastName',
                                'GroupType' => 'Person',
                                'GroupID' => '',
                                'Value' => 'de Tester',
                            ],
                            [
                                'Name' => 'Gender',
                                'GroupType' => 'Person',
                                'GroupID' => '',
                                'Value' => '1',
                            ],
                            [
                                'Name' => 'Culture',
                                'GroupType' => 'Person',
                                'GroupID' => '',
                                'Value' => 'nl-NL',
                            ],
                            [
                                'Name' => 'BirthDate',
                                'GroupType' => 'Person',
                                'GroupID' => '',
                                'Value' => '01-01-1990',
                            ],
                            [
                                'Name' => 'Street',
                                'GroupType' => 'Address',
                                'GroupID' => '',
                                'Value' => 'Hoofdstraat',
                            ],
                            [
                                'Name' => 'HouseNumber',
                                'GroupType' => 'Address',
                                'GroupID' => '',
                                'Value' => '90',
                            ],
                            [
                                'Name' => 'ZipCode',
                                'GroupType' => 'Address',
                                'GroupID' => '',
                                'Value' => '8441ER',
                            ],

                            [
                                'Name' => 'City',
                                'GroupType' => 'Address',
                                'GroupID' => '',
                                'Value' => 'Heerenveen',
                            ],
                            [
                                'Name' => 'Country',
                                'GroupType' => 'Address',
                                'GroupID' => '',
                                'Value' => 'NL',
                            ],
                            [
                                'Name' => 'Email',
                                'GroupType' => 'Email',
                                'GroupID' => '',
                                'Value' => 'xxx@xxx.nl',
                            ],
                            [
                                'Name' => 'Mobile',
                                'GroupType' => 'Phone',
                                'GroupID' => '',
                                'Value' => '0612345678',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $params;
    }


}