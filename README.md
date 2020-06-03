# Laravel Buckaroo

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lamalama/laravel-buckaroo.svg?style=flat-square)](https://packagist.org/packages/lamalama/laravel-buckaroo)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![StyleCI](https://github.styleci.io/repos/268217938/shield?branch=master)](https://github.styleci.io/repos/268217938)
[![Total Downloads](https://img.shields.io/packagist/dt/lamalama/laravel-buckaroo.svg?style=flat-square)](https://packagist.org/packages/lamalama/laravel-buckaroo)

> :warning: **This package is in a preliminary development phase and not stable**: Do not use in production!

Make your Eloquent models wishlistable.

## Install

Via Composer

``` bash
$ composer require lamalama/laravel-buckaroo
```

You can publish the migration with:
```bash
php artisan vendor:publish --provider="LamaLama\Buckaroo\BuckarooServiceProvider" --tag="migrations"
```

After publishing the migration you can create the `wishlist` table by running the migrations:

```bash
php artisan migrate
```

You can optionally publish the config file with:
```bash
php artisan vendor:publish --provider="LamaLama\Buckaroo\BuckarooServiceProvider" --tag="config"
```

## Use

Create a subscription:

```php
Buckaroo::subscriptions()->create([
    'amount' => '10.00',
    'interval' => 'monthly'
]);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Mark de Vries](https://github.com/lamalamaMark)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
