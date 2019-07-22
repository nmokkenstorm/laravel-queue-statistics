<?php

namespace Nmokkenstorm\LaravelQueueStatistics;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

use Illuminate\Contracts\Queue\Factory;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        Publisher::class                          => Publisher::class,
        Contracts\FlushStrategy::class            => StackCountFlushStrategy::class,
        Contracts\PublishesQueueStatistics::class => Publishers\LogPublisher::class 
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'queue-monitor'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->extend('queue', function ($factory, $app) {
            return new QueueFactoryDecorator($factory, $app->make(Publisher::class));
        });

        $this->app->when(StackCountFlushStrategy::class)
                  ->needs('$threshold')
                  ->give((int)data_get($this->app, 'config.queue-monitor.stack-threshold'));
    } 
}
