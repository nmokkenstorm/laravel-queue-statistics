<?php

namespace Nmokkenstorm\LaravelQueueStatistics;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

use Illuminate\Contracts\Queue\Factory;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->extend('queue', function ($factory) {
            return new QueueFactoryDecorator($factory);
        });
    } 
}
