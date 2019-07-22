<?php

namespace Nmokkenstorm\LaravelQueueStatistics\Contracts;

interface PublishesQueueStatistics
{
    /**
     * Write a bunch of job events to the statistics
     * backend.
     *
     * @param array $jobs
     * @return void
     */
    public function flush(array $jobs) : void;
}
