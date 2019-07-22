<?php

namespace Nmokkenstorm\LaravelQueueStatistics\Publishers;

use Illuminate\Database\Connection;
use Illuminate\Support\Collection;

use Nmokkenstorm\LaravelQueueStatistics\Contracts\PublishesQueueStatistics;

class DatabasePublisher implements PublishesQueueStatistics
{
    /**
     * @var \Illuminate\Database\Connection
     */
    private $connection;

    /**
     * @param \Illuminate\Database\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
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
        $jobs = collect($jobs)->partition(function ($job) {
            return $job['event'] == 'queued';
        });

        $jobs[0]->unlessEmpty(function ($jobs) {
            $this->writeNewJobs($jobs);
        });

        $jobs[1]->unlessEmpty(function ($jobs) {
            $this->updateExistingJobs($jobs);
        });
    }

    /**
     * @param \Illuminate\Support\Collection $jobs
     * @return void
     */
    protected function writeNewJobs(Collection $jobs)
    {
        $this->connection
             ->table('queue_statistics')
             ->insert(
                 $jobs->map(function ($job) {
                     return [
                         'id'        => $job['id'],
                         'job'       => $job['job'],
                         'queue'     => $job['queue'],
                         'queued'    => $job['timestamp']
                     ];
                 })->all()
             );
    }

    /**
     * @param \Illuminate\Support\Collection $jobs
     * @return void
     */
    protected function updateExistingJobs(Collection $jobs)
    {
        $jobs->each(function ($job) {
            $this->connection
                 ->table('queue_statistics')
                 ->where('id', '=', $job['id'])
                 ->update(['started' => $job['timestamp']]);
        });
    }
}
