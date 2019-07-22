<?php

namespace Nmokkenstorm\LaravelQueueStatistics\Publishers;

use Illuminate\Log\LogManager;
use Nmokkenstorm\LaravelQueueStatistics\Contracts\PublishesQueueStatistics;

class LogPublisher implements PublishesQueueStatistics
{
    /**
     * @var \Illuminate\Log\LogManager
     */
    private $log;

    /**
     * @param \Illuminate\Log\LogManager $log
     */
    public function __construct(LogManager $log)
    {
        $this->log = $log;
    }
    
    /**
     * Write a bunch of job events to the statistics
     * backend.
     *
     * @param array $jobs
     * @return void
     */
    public function flush(array $jobs) : void
    {
        foreach($jobs as $job) {
            $this->log->info($job);
        }
    }
}
