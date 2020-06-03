<?php

namespace LamaLama\Buckaroo;

class Subscriptions extends BuckarooFactory
{
    /**
     * create
     * @param  array $params
     * @return void
     */
    public function create($params)
    {
        $params = [
            'Services' => [
                'ServiceList' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'CreateSubscription',
                        'Parameters' => [
                            [
                                'Name' => 'StartDate',
                                'GroupType' => 'Addrateplan',
                                'GroupID' => '',
                                'Value' => '11-07-2017',
                            ],
                            [
                                'Name' => 'RatePlanCode',
                                'GroupType' => 'Addrateplan',
                                'GroupID' => '',
                                'Value' => 'xxxxxx',
                            ],
                            [
                                'Name' => 'Code',
                                'GroupType' => 'Debtor',
                                'GroupID' => '',
                                'Value' => 'xxxxxx',
                            ],
                            [
                                'Name' => 'ConfigurationCode',
                                'Value' => 'xxxxx',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $params;
    }

    /**
     * createCombined
     * @param  array $params
     * @return void
     */
    public function createCombined($params)
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
                                'Value' => '03-08-2017',
                            ],
                            [
                                'Name' => 'RatePlanCode',
                                'GroupType' => 'AddRatePlan',
                                'GroupID' => '',
                                'Value' => 'gvn1f9xx',
                            ],
                            [
                                'Name' => 'ConfigurationCode',
                                'Value' => '9wqe32xx',
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

    /**
     * get
     * @param  array $params
     * @return void
     */
    public function get($params)
    {
        $params = [
            'Services' => [
                'ServiceList' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'SubscriptionInfo',
                        'Parameters' => [
                            [
                                'Name' => 'SubscriptionGuid',
                                'Value' => '6ABDB214C4944B5C8638420CE9ECxxxx',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $params;
    }

    /**
     * cancel
     * @param  array $params
     * @return void
     */
    public function cancel($params)
    {
        $params = [
            'Services' => [
                'ServiceList' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'StopSubscription',
                        'Parameters' => [
                            [
                                'Name' => 'SubscriptionGuid',
                                'Value' => 'A8A3DF828F0E4706B50191D3D1C88xxx',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $params;
    }

    /**
     * update
     * @param  array $params
     * @return void
     */
    public function update($params)
    {
        $params = [
            'Services' => [
                'ServiceList' => [
                    [
                        'Name' => 'Subscriptions',
                        'Action' => 'UpdateSubscription',
                        'Parameters' => [
                            [
                                'Name' => 'SubscriptionGuid',
                                'Value' => 'FC512FC9CC3A485D8CF3D1804FF6xxxx',
                            ],
                            [
                                'Name' => 'ConfigurationCode',
                                'Value' => '9wqe32ew',
                            ],
                            [
                                'Name' => 'RatePlanGuid',
                                'GroupType' => 'UpdateRatePlan',
                                'GroupID' => '',
                                'Value' => 'F075470B1BB24B9291943A888A2Fxxxx',
                            ],
                            [
                                'Name' => 'StartDate',
                                'GroupType' => 'UpdateRatePlan',
                                'GroupID' => '',
                                'Value' => '03-08-2017',
                            ],
                            [
                                'Name' => 'EndDate',
                                'GroupType' => 'UpdateRatePlan',
                                'GroupID' => '',
                                'Value' => '03-09-2017',
                            ],
                            [
                                'Name' => 'RatePlanChargeGuid',
                                'GroupType' => 'UpdateRatePlanCharge',
                                'GroupID' => '',
                                'Value' => 'AD375E2E188747159673440898B9xxxx',
                            ],
                            [
                                'Name' => 'BaseNumberOfUnits',
                                'GroupType' => 'UpdateRatePlanCharge',
                                'GroupID' => '',
                                'Value' => '1',
                            ],
                            [
                                'Name' => 'PricePerUnit',
                                'GroupType' => 'UpdateRatePlanCharge',
                                'GroupID' => '',
                                'Value' => '10.00',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $params;
    }
}
