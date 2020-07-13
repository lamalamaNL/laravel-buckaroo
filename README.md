## Todo

Next up!!
- [x] Redirects goed in regelen
- [ ] Webhook fixen event goed regelen
- [ ] Beter naming voor buckaroo db velden
- [ ] Verschillende payment methods per land
	- [ ] Voeg response van webhook to
	- [ ] enum voor statussen
	- [ ] service --> payment method
- [ ] Tests toevoegen voor validatie
- [ ] CORS open
- [ ] Redirect url in de call meenemen
- [ ] Bedrag opnemen in redirect URL
- [ ] Update Documentatie
- [ ] Deploy prod 
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
];
```

## Usage

``` php
$skeleton = new LamaLama\LaravelBuckaroo();
echo $skeleton->echoPhrase('Hello, Spatie!');
```

# Package development

To test against the actual buckaroo API create a file
```testing_credentials.php``` in the root of the project
and add the following:
```php
<?php

$buckaroo_api_key = '....';
$buckaroo_api_secret = '....';

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
