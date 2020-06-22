<?php

include('testing_credentials.php');

return [
    'endpoint' =>  env('BUCKAROO_API_ENDPOINT', 'https://testcheckout.buckaroo.nl'),
    'key' =>  env('BUCKAROO_API_KEY', $buckaroo_api_key),
    'secret' =>  env('BUCKAROO_API_SECRET', $buckaroo_api_secret),
    'returnURL' =>  env('BUCKAROO_RETURN_URL', '/'),
    'returnURLCancel' =>  env('BUCKAROO_CANCEL_URL', '/'),
    'returnURLError' =>  env('BUCKAROO_ERROR_URL', '/'),
    'returnURLReject' =>  env('BUCKAROO_REJECT_URL', '/'),
    'invoiceTitle' =>  env('BUCKAROO_INVOICE_TITLE', 'new invoice'),
];
