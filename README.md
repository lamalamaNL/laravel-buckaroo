# 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-buckaroo-ed.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-buckaroo-ed)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-buckaroo-ed/run-tests?label=tests)](https://github.com/spatie/laravel-buckaroo-ed/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-buckaroo-ed.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-buckaroo-ed)


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


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

Learn how to create a package like this one, by watching our premium video course:

[![Laravel Package training](https://spatie.be/github/package-training.jpg)](https://laravelpackage.training)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/package-skeleton-laravel
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="LamaLama\LaravelBuckaroo\SkeletonServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="LamaLama\LaravelBuckaroo\SkeletonServiceProvider" --tag="config"
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
