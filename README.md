## Todo

Next up!!
- [x] Redirects goed in regelen
- [x] Webhook fixen event goed regelen
- [x] Verschillende payment methods per land
- [x] CORS open (use cors.php in application)
- [x] Redirect url in de call meenemen
- [x] Bedrag opnemen in redirect URL
- [x] Beter naming voor buckaroo db velden
    - [x] Fix model relations (payment en subscriptions moeten ook een elkaar gelinkt kunnen worden)
	- [x] Voeg response van webhook to
	- [x] enum voor statussen
	- [x] service --> payment method
- [x] Tests toevoegen voor validatie en andere nieuwe features
- [ ] Update Documentatie
- [x] Deploy prod 
- [ ] (Mark): Implement in wordpress
- [ ] Test test test!! 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-buckaroo-ed.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-buckaroo-ed)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-buckaroo-ed/run-tests?label=tests)](https://github.com/spatie/laravel-buckaroo-ed/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-buckaroo-ed.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-buckaroo-ed)


## Running tests
```
composer test
```


## Exception handling
The package will throw a BuckarooApiException when things 
go wrong with API calls to buckaroo. If not catched an json 
reponse will be rendered. If your application is in debug 
mode the full response from the Buckaroo will be 
rendered (hopefully indication whats going wrong).
```
Todo: Add error example
``` 

## Object you should know
``LamaLama\LaravelBuckaroo\Api\Action``: The result of an
buckaroo API request. 

``LamaLama\LaravelBuckaroo\Buckaroo``: Where the subscribeAndPay, oneTimePayment and fetchPaymentMethods functions reside. 

``LamaLama\LaravelBuckaroo\Controllers\BuckarooControllers``: The controller that provides the subscriptions and payment methods






-------
-------
-------
-------

## Installation

You can install the package via composer:

```bash
composer require lamalamaNL/laravel-buckaroo
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="LamaLama\LaravelBuckaroo\BuckarooServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="LamaLama\LaravelBuckaroo\BuckarooServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
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
            'ratePlanCode' => env('BUCKAROO_PRODUCT_CODE_MONTHLY5', ''), // plaza.buckaroo.nl --> configuration --> Subscriptions --> products --> Rate plan -> Rate plan code
            'configurationCode' => env('BUCKAROO_CONFIGURATION_CODE', ''), // plaza.buckaroo.nl --> configuration --> Subscriptions -> configurations -> Configuration code
        ],
        'monthly_25' => [
            'key' => 'monthly_25', // Unique key to identity subscription
            'name' => 'Vijfentwintig euro per maand',
            'amount' => 25,
            'ratePlanCode' => env('BUCKAROO_PRODUCT_CODE_TWENTY_FIVE_MONTHLY', ''), // plaza.buckaroo.nl --> configuration --> Subscriptions --> products --> Rate plan -> Rate plan code
            'configurationCode' => env('BUCKAROO_CONFIGURATION_CODE', ''), // plaza.buckaroo.nl --> configuration --> Subscriptions -> configurations -> Configuration code
        ],
        'daily_25c' => [
            'key' => 'daily_25c', // Unique key to identity subscription
            'name' => 'Vijfentwintig euro cent per dag',
            'amount' => 0.25,
            'ratePlanCode' => env('BUCKAROO_PRODUCT_CODE_TWENTY_FIVE_CENT_DAILY', ''), // plaza.buckaroo.nl --> configuration --> Subscriptions --> products --> Rate plan -> Rate plan code
            'configurationCode' => env('BUCKAROO_CONFIGURATION_CODE', ''), // plaza.buckaroo.nl --> configuration --> Subscriptions -> configurations -> Configuration code
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

```

Make sure to setup the paymentMethods & subscriptions in the config file accordingly to your situation.

## Usage
Setup your pay/subscribe routes
``` php 

Route::post('pay', 'PaymentController@pay');
Route::post('subscribe', 'PaymentController@subscribe');
```

Create a controller which handles the pay/subscribe routes, a quick example of the PaymentController class is shown below:
``` php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LamaLama\LaravelBuckaroo\Buckaroo;
use LamaLama\LaravelBuckaroo\Customer;
use LamaLama\LaravelBuckaroo\Payment;
use LamaLama\LaravelBuckaroo\Subscription;

class PaymentController extends Controller
{

    public function pay(Buckaroo $buckaroo, Request $request)
    {

        $customer = new Customer();
        $customer->email = 'test@test.com';
        $customer->ip = $request->ip();
        $customer->save();

        $payment = new Payment();
        $payment->setAmount($request->get('amount'))
            ->setPaymentmethod($request->get('method'), $request->get('issuer', null));
        $payment->setSuccessRedirectUrl($request->get('redirectSuccessUrl', null));
        $payment->setNoSuccessRedirectUrl($request->get('redirectNoSuccessUrl', null));

        $paymentInfo = $buckaroo->oneTimePayment($customer, $payment);
        return response()->json([
            'redirect' => $paymentInfo->getRedirectUrl()
        ]);
    }


    public function subscribe(Buckaroo $buckaroo, Request $request)
    {
        $customerProps = $request->get('customer');
        $customer = new Customer($customerProps);
        $customer->ip = $request->ip();
        $customer->save();

        $sub = Subscription::createByConfigKey($request->get('subscription', null), $customer);

        $payment = new Payment();
        $payment->setAmount($sub->amount)
            ->setPaymentmethod($request->get('method'), $request->get('issuer', null));
        $payment->subscription_id = $sub->id;
        $payment->setSuccessRedirectUrl($request->get('redirectSuccessUrl', null));
        $payment->setNoSuccessRedirectUrl($request->get('redirectNoSuccessUrl', null));

        $buckarooResponse = $buckaroo->subscribeAndPay($customer, $sub, $payment);
        return response()->json([
            'redirect' => $buckarooResponse->getRedirectUrl()
        ]);
    }
}

```

In the front-end you can call the following two endpoints:
This call will give you all the paymentMethods that are setup in the config file (paramaters are optional):
``` php
api/buckaroo/paymentmethods?locale=en&paymentType=recurring
```

This call will give you all the subscription that are setup in the config file
``` php
api/buckaroo/subscriptions
```

# Package development

To test against the actual buckaroo API create a file
```testing_credentials.php``` in the root of the project
and add the following:
```php
<?php

$buckaroo_api_key = '....';
$buckaroo_api_secret = '....';
$buckaroo_api_subscription_ratePlanCode = '....';
$buckaroo_api_subscription_configurationCode = '....';

```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [LamaLama](https://github.com/LamaLamaNL)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
