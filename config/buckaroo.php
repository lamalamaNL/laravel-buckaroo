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
    // TODO: @Delano
    /*
     * Na een betaling redirect Buckaroo naar 1 van deze naar deze urls afhankelijk van de status van
     * de status van de payment
     * /payment/success
     * /payment/cancel
     * /payment/error
     * /payment/reject
     *
     * Deze urls moeten geconfigureerd kunnen in buckaroo:
     * https://plaza.buckaroo.nl/Configuration/WebSite/Index/.
     *
     */
    'BucckarooReturnURL' =>  env('BUCKAROO_RETURN_URL', 'http://website.org/payment/success'),
    'BucckarooReturnURLCancel' =>  env('BUCKAROO_CANCEL_URL', 'http://website.org/payment/cancel'),
    'BucckarooReturnURLError' =>  env('BUCKAROO_ERROR_URL', 'http://website.org/payment/error'),
    'BucckarooReturnURLReject' =>  env('BUCKAROO_REJECT_URL', 'http://website.org/payment/reject'),
    /*
     * En vervolgens moeten deze url afgevangen worden in de package. En deze endpoints moeten op hun beurt
     * weer naar de client url redirecten (op basis can config
     */
    'clientSuccessURL' =>  env('CLIENT_SUCCESS_URL', 'http://website.org/payment/success'),
    'BucckarooFailedURL' =>  env('CLIENT_FAILURE_URL', 'http://website.org/payment/failed'),

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
