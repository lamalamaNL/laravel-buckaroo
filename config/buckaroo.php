<?php

return [

    /*
     * API andCredentials
     */
    'endpoint' =>  env('BUCKAROO_API_ENDPOINT', 'https://testcheckout.buckaroo.nl'),
    'key' =>  env('BUCKAROO_API_KEY', ''),
    'secret' =>  env('BUCKAROO_API_SECRET', ''),

    /*
     * This package inject multiple api routes into your application. Here you can configure
     * the base url
     */
    'url_namespace' => '/api/buckaroo',

    /*
     * After successfull or unsuccessfull payments user gets redirect to a page.
     * Here you can configure the urls to use
     */
    'redirects' => [
        'clientSuccessUrl' =>  env('CLIENT_SUCCESS_URL', 'http://website.org/payment/success'),
        'clientNoSuccessUrl' =>  env('CLIENT_FAILURE_URL', 'http://website.org/payment/failed'),
    ],

    /*
     * Invoice configuration
     */
    'invoiceTitle' =>  env('BUCKAROO_INVOICE_TITLE', 'Default invoice title'),


    /*
     * Payment methods
     * Use the keys from: https://dev.buckaroo.nl/PaymentMethods/Description/afterpay#top
     * Make sure all option you add are also activated in Buckaroo Plaza
     */
    'paymentMethods' => [
        'single' => [
            'nl' => [
                'mastercard',
                'visa',
                'amex',
                'bancontactmrcash',
                'ideal',
                'paypal',
            ],
            'de' => [
                'mastercard',
                'visa',
                'amex',
                'paypal',
                'sofortueberweisung',
            ],
            'en' => [
                'mastercard',
                'visa',
                'amex',
                'bancontactmrcash',
                'sepa',
                'ideal',
                'paypal',
                'sofortueberweisung',
                'giropay'
            ],
            'default' => [
                'mastercard',
                'visa',
                'amex',
                'paypal',
            ]
        ],
        'recurring' => [
            'nl' => [
                'mastercard',
                'visa',
                'amex',
                'ideal',
                'paypal',
            ],
            'de' => [
                'mastercard',
                'visa',
                'amex',
                'paypal',
            ],
            'en' => [
                'mastercard',
                'visa',
                'amex',
                'ideal',
                'paypal'
            ],
            'default' => [
                'mastercard',
                'visa',
                'amex',
                'paypal',
            ]
        ],
        'default' => [
            'mastercard',
            'visa',
            'amex',
            'paypal',
        ]
    ],

    /*
     * Subscriptions
     */
    'subscriptions' => [
        'monthly_5' => [
            'key' => 'monthly_5', // Unique key to identity subscription
            'name' => 'Vijf euro per maand',
            'amount' => 5,
            'ratePlanCode' => env('BUCKAROO_PRODUCT_CODE_FIVE_MONTHLY', ''), // plaza.buckaroo.nl --> configuration --> Subscriptions --> products --> Rate plan -> Rate plan code
            'configurationCode' => env('BUCKAROO_CONFIGURATION_CODE', '') // plaza.buckaroo.nl --> configuration --> Subscriptions -> configurations -> Configuration code
        ],
        'monthly_25' => [
            'key' => 'monthly_25', // Unique key to identity subscription
            'name' => 'Vijfentwintig euro per maand',
            'amount' => 25,
            'ratePlanCode' => env('BUCKAROO_PRODUCT_CODE_TWENTY_FIVE_MONTHLY', ''),
            'configurationCode' => env('BUCKAROO_CONFIGURATION_CODE', '')
        ],
        'daily_25c' => [
            'key' => 'daily_25c', // Unique key to identity subscription
            'name' => 'Vijfentwintig euro cent per dag',
            'amount' => 0.25,
            'ratePlanCode' => env('BUCKAROO_PRODUCT_CODE_TWENTY_FIVE_CENT_DAILY', ''),
            'configurationCode' => env('BUCKAROO_CONFIGURATION_CODE', '')
        ]
    ],

    /*
     * Cache settings
     */
    'cache' => [
        /*
         * The amount of time payment method information from buckaroo is cached in seconds
         * We do this the reduce the number of API calls and speed up the API
         */
        'paymentOptionsCachePeriode' => 60 * 24
    ]
];
