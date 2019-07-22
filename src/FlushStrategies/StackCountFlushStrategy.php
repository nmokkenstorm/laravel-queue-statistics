<?php

namespace Nmokkenstorm\LaravelQueueStatistics;

use Nmokkenstorm\LaravelQueueStatistics\Contracts\FlushStrategy;

class StackCountFlushStrategy implements FlushStrategy
{
    /**
     * @var int
     */
    private $threshold;

    /**
     * @param int
     */
    public function __construct(int $threshold)
    {
        $this->threshold = $threshold;
    }

    /**
     * Based on a stack of jobs, decide if we need to report
     * to the statistics backend
     *
     * @param array $jobs
     * @return bool
     */
    public function shouldFlush(array $jobs) : bool
    {
        return (count($jobs) >= $this->threshold);
    }
}
