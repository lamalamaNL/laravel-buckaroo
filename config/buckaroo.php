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
        'clientFailedUrl' =>  env('CLIENT_FAILURE_URL', 'http://website.org/payment/failed'),
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
        'mastercard',
        'visa',
        'amex',
        'bancontactmrcash',
        'eps',
        'ideal',
        'paypal',
        'sofortueberweisung',
    ],

    /*
     * Subscriptions
     */
    'subscriptions' => [
        [
            'key' => 'montly_5', // Unique key to identity subscription
            'name' => 'Vijf euro per maand',
            'amount' => 5,
            'ratePlanCode' => '', // plaza.buckaroo.nl --> configuration --> Subscriptions --> products --> Rate plan -> Rate plan code
            'configurationCode' => '', // plaza.buckaroo.nl --> configuration --> Subscriptions -> configurations -> Configuration code
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
