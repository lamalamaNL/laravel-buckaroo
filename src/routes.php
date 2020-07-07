<?php

Route::prefix('buckaroo')->group(function () {
    Route::post('get-payment-options', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@getPaymentOptions');
    Route::post('single-donation', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@makeSingleDonation');
    Route::post('subscribe', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@subscribe');
 
    Route::post('webhook', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@webhook');

    Route::post('success', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@redirectSuccess');
    Route::post('cancel', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@redirectCancel');
    Route::post('error', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@redirectError');
    Route::post('reject', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@redirectReject');
});
