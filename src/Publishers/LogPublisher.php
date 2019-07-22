<?php

namespace Nmokkenstorm\LaravelQueueStatistics\Publishers;

use Nmokkenstorm\LaravelQueueStatistics\Contracts\PublishesQueueStatistics;

class LogPublisher implements PublishesQueueStatistics
{
    
    /**
     * Write a bunch of job events to the statistics
     * backend.
     *
     * @param array $jobs
     * @return void
     */
    public function flush(array $jobs) : void
    {
        echo print_r($jobs, 1);
    }
}
