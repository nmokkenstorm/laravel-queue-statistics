<?php

namespace Nmokkenstorm\LaravelQueueStatistics;

use Illuminate\Contracts\Queue\Factory;

class QueueFactoryDecorator implements Factory
{
    /**
     * @var \Illuminate\Contracts\Queue\Factory
     */
    private $factory;

    /**
     * @var \Nmokkenstorm\LaravelQueueStatistics\Publisher
     */
    private $publisher;

    /**
     * @param \Illuminate\Contracts\Queue\Factory $factory
     * @param \Nmokkenstorm\LaravelQueueStatistics\Publisher
     */
    public function __construct(Factory $factory, Publisher $publisher)
    {
        $this->factory      = $factory;
        $this->publisher    = $publisher;
    }
    
    /**
     * Resolve a queue connection instance.
     *
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connection($name = null)
    {
        return new QueueEventDecorator($this->factory->connection($name), $this->publisher);
    }

    /**
     * Dynamically pass calls to the default connection.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->factory->$method(...$parameters);
    }
}
