<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Support\ServiceProvider;

// use LamaLama\LaravelBuckaroo\Commands\SkeletonCommand;

class BuckarooServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->register(EventServiceProvider::class);
        /*
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/skeleton.php' => config_path('skeleton.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/skeleton'),
            ], 'views');

            if (! class_exists('CreatePackageTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_skeleton_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_skeleton_table.php'),
                ], 'migrations');
            }

            $this->commands([
                SkeletonCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'skeleton');
        */
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/buckaroo.php', 'buckaroo');
        $this->app->bind(Buckaroo::class, function () {
            return new Buckaroo($this->app->make(ApiClient::class));
        });
    }
}
