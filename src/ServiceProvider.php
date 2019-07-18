<?php

namespace Nmokkenstorm\LaravelQueueStatistics;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

use Illuminate\Queue\QueueManager;
use Illuminate\Contracts\Queue\Queue;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $decorator = function (Queue $queue, Container $app) {
            return new QueueEventDecorator($queue);
        };
        
        $this->app->resolving(Queue::class, $decorator);
    } 
}
