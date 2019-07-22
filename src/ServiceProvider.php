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
        Contracts\FlushStrategy::class            => FlushStrategies\StackCountFlushStrategy::class,
        Contracts\PublishesQueueStatistics::class => Publishers\DatabasePublisher::class 
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
        
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app
             ->when(StackCountFlushStrategy::class)
             ->needs('$threshold')
             ->give( (int) data_get($this->app, 'config.queue-monitor.stack-threshold'));

        $this->app->extend('queue', function ($factory) {
        
            return new QueueFactoryDecorator($factory, $this->app->make(Publisher::class));
        
        });

    } 
}
