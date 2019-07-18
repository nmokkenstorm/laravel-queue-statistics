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
     * @param \Illuminate\Contracts\Queue\Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }
    
    /**
     * Resolve a queue connection instance.
     *
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connection($name = null)
    {
        return new QueueEventDecorator($this->factory->connection($name));
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
