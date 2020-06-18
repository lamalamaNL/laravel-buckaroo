<?php

include('testing_credentials.php');

return [
    'endpoint' =>  env('BUCKAROO_API_ENDPOINT', 'https://testcheckout.buckaroo.nl'),
    'key' =>  env('BUCKAROO_API_KEY', $buckaroo_api_key),
    'secret' =>  env('BUCKAROO_API_SECRET', $buckaroo_api_secret),
];
