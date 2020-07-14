<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Support\ServiceProvider;

// use LamaLama\LaravelBuckaroo\Commands\SkeletonCommand;

class BuckarooServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__.'/routes.php');


        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/buckaroo.php' => config_path('buckaroo.php'),
            ], 'config');

            //$this->publishes([
            //        __DIR__ . '/../database/migrations/2020_06_18_100144_create_customers_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_customers_table.php'),
            //        __DIR__ . '/../database/migrations/2020_06_18_101151_create_payments_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_payment_table.php'),
            //        __DIR__ . '/../database/migrations/2020_06_18_100936_create_subscriptions_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_subscriptions_table.php'),
            //    ], 'migrations');

            /*
            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/skeleton'),
            ], 'views');

            $this->commands([
                SkeletonCommand::class,
            ]);
            */
        }

        //$this->loadViewsFrom(__DIR__.'/../resources/views', 'skeleton');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/buckaroo.php', 'buckaroo');
        $this->app->bind(Buckaroo::class, function () {
            return new Buckaroo($this->app->make(ApiClient::class));
        });

        //$this->app->make('LamaLama\LaravelBuckaroo\Controllers\BuckarooController');
    }
}
