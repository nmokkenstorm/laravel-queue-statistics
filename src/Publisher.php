<?php

namespace Nmokkenstorm\LaravelQueueStatistics;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Contracts\Container\Container;

use Illuminate\Support\InteractsWithTime;

use Nmokkenstorm\LaravelQueueStatistics\Contracts\FlushStrategy;
use Nmokkenstorm\LaravelQueueStatistics\Contracts\PublishesQueueStatistics;

class Publisher
{
    use InteractsWithTime;

    /**
     * @param \Nmokkenstorm\LaravelQueueStatistics\Contracts\FlushStrategy
     */
    private $flushStrategy;

    /**
     * @param \Nmokkenstorm\LaravelQueueStatistics\Contracts\PublishesQueueStatistics
     */
    private $publisher;

    /**
     * @var array
     */
    private $stack = [];

    /**
     * @param \Nmokkenstorm\LaravelQueueStatistics\Contracts\FlushStrategy $flushStrategy
     * @param \Nmokkenstorm\LaravelQueueStatistics\Contracts\PublishesQueueStatistics $publisher
     */
    public function __construct(FlushStrategy $flushStrategy, PublishesQueueStatistics $publisher)
    {
        $this->flushStrategy = $flushStrategy;
        $this->publisher     = $publisher;
    }

    /**
     * @param string $id
     * @param string $job
     */
    public function publish(string $id, string $job, string $event, $queue = 'default')
    {
        $this->stack[] = [
            'id'        => $id,
            'event'     => $event,
            'queue'     => $queue,
            'timestamp' => $this->currentTime()
        ];

        if ($this->flushStrategy->shouldFlush($this->stack)) {

            $this->publisher->flush($this->stack);
            $this->stack = [];

        }
    }

    /**
     * @return void
     */
    public function __destruct() 
    {
        $this->publisher->flush($this->stack);
    } 

}
