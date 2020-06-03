<?php

namespace LamaLama\Buckaroo;

use Illuminate\Support\ServiceProvider;
use LamaLama\Buckaroo\BuckarooFactory;

class BuckarooServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishables();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/buckaroo.php', 'buckaroo');

        $this->app->bind('buckaroo', function () {
            return new BuckarooFactory();
        });
    }

    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__.'/../config/wishlist.php' => config_path('wishlist.php'),
        ], 'config');

        if (! class_exists('CreateWishlistTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_wishlist_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_wishlist_table.php'),
            ], 'migrations');
        }
    }
}
