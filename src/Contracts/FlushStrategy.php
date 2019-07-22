<?php

namespace Nmokkenstorm\LaravelQueueStatistics\Contracts;

interface FlushStrategy
{
    /**
     * Based on a stack of jobs, decide if we need to report
     * to the statistics backend
     *
     * @param array $jobs
     * @return bool
     */
    public function shouldFlush(array $jobs) : bool;
}
