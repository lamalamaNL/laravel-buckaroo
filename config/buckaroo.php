<?php

include('testing_credentials.php');

return [
    'endpoint' =>  env('BUCKAROO_API_ENDPOINT', 'https://testcheckout.buckaroo.nl'),
    'key' =>  env('BUCKAROO_API_KEY', $buckaroo_api_key),
    'secret' =>  env('BUCKAROO_API_SECRET', $buckaroo_api_secret),
    'returnURL' =>  env('BUCKAROO_RETURN_URL', 'http://website.org/payment/success'),
    'returnURLCancel' =>  env('BUCKAROO_CANCEL_URL', 'http://website.org/payment/failed'),
    'returnURLError' =>  env('BUCKAROO_ERROR_URL', 'http://website.org/payment/failed'),
    'returnURLReject' =>  env('BUCKAROO_REJECT_URL', 'http://website.org/payment/failed'),
    'invoiceTitle' =>  env('BUCKAROO_INVOICE_TITLE', 'Default invoice title'),
];
