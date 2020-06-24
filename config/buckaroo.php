<?php

include('testing_credentials.php');

return [

    /*
     * API andCredentials
     */
    'endpoint' =>  env('BUCKAROO_API_ENDPOINT', 'https://testcheckout.buckaroo.nl'),
    'key' =>  env('BUCKAROO_API_KEY', $buckaroo_api_key),
    'secret' =>  env('BUCKAROO_API_SECRET', $buckaroo_api_secret),

    /*
     * Return url configuration
     */
    'returnURL' =>  env('BUCKAROO_RETURN_URL', 'http://website.org/payment/success'),
    'returnURLCancel' =>  env('BUCKAROO_CANCEL_URL', 'http://website.org/payment/failed'),
    'returnURLError' =>  env('BUCKAROO_ERROR_URL', 'http://website.org/payment/failed'),
    'returnURLReject' =>  env('BUCKAROO_REJECT_URL', 'http://website.org/payment/failed'),

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
