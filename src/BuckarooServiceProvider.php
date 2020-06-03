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
            __DIR__.'/../config/buckaroo.php' => config_path('buckaroo.php'),
        ], 'config');

        if (! class_exists('CreateSubscriptionsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_subscriptions_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_subscriptions_table.php'),
            ], 'migrations');
        }
    }
}
