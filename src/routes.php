<?php

Route::prefix(config('buckaroo.url_namespace'))->middleware('api')->group(function () {
    Route::get('paymentmethods', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@getPaymentmethods')->name('buckaroo.paymentmethods');
    Route::get('subscriptions', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@getSubscriptions')->name('buckaroo.subscriptions');
 
    Route::post('webhook', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@webhook')->name('buckaroo.config.webhook');

    Route::any('success', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@redirectSuccess')->name('buckaroo.config.success');
    Route::any('cancel', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@redirectCancel')->name('buckaroo.config.cancel');
    Route::any('error', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@redirectError')->name('buckaroo.config.error');
    Route::any('reject', 'LamaLama\LaravelBuckaroo\Controllers\BuckarooController@redirectReject')->name('buckaroo.config.reject');
});
