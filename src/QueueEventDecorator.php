<?php

namespace Nmokkenstorm\LaravelQueueStatistics;

use Illuminate\Contracts\Queue\Queue;
use Illuminate\Contracts\Queue\Job;

class QueueEventDecorator implements Queue
{
    /**
     * @var \Illuminate\Contracts\Queue\Queue
     */
    private $queue;

    /**
     * @var \Nmokkenstorm\LaravelQueueStatistics\Publisher
     */
    private $publisher;

    /**
     * @param \Illuminate\Contracts\Queue\Queue $queue
     * @param \Nmokkenstorm\LaravelQueueStatistics\Publisher
     */
    public function __construct(Queue $queue, Publisher $publisher)
    {
        $this->queue     = $queue;
        $this->publisher = $publisher;
    }

    /**
     * Get the size of the queue.
     *
     * @param  string|null  $queue
     * @return int
     */
    public function size($queue = null)
    {
        return $this->queue->size($queue); 
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string|object  $job
     * @param  mixed   $data
     * @param  string|null  $queue
     * @return mixed
     */
    public function push($job, $data = '', $queue = null)
    {
        $result = $this->queue->push($job, $data, $queue);

        $this->publisher->publish( (string) $result, get_class($job), 'queued', $queue);

        return $result;
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        if ($job = $this->queue->pop($queue)) {
            $this->publisher->publish((string)$job->getJobId(), get_class($job), 'started', $queue);
        }
        
        return $job;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string  $queue
     * @param  string|object  $job
     * @param  mixed   $data
     * @return mixed
     */
    public function pushOn($queue, $job, $data = '')
    {
        return $this->queue->pushOn($queue, $job, $data);
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param  string  $payload
     * @param  string|null  $queue
     * @param  array   $options
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        return $this->queue->pushRaw($payload, $queue, $options);
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @param  string|object  $job
     * @param  mixed   $data
     * @param  string|null  $queue
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        return $this->queue->later($delay, $job, $data, $queue);
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  string  $queue
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @param  string|object  $job
     * @param  mixed   $data
     * @return mixed
     */
    public function laterOn($queue, $delay, $job, $data = '')
    {
        return $this->queue->laterOn($queue, $delay, $job, $data);
    }

    /**
     * Push an array of jobs onto the queue.
     *
     * @param  array   $jobs
     * @param  mixed   $data
     * @param  string|null  $queue
     * @return mixed
     */
    public function bulk($jobs, $data = '', $queue = null)
    {
        return $this->queue->bulk($jobs, $data, $queue);
    }

    /**
     * Get the connection name for the queue.
     *
     * @return string
     */
    public function getConnectionName()
    {
        return $this->queue->getConnectionName();
    }

    /**
     * Set the connection name for the queue.
     *
     * @param  string  $name
     * @return $this
     */
    public function setConnectionName($name)
    {
        return $this->queue->setConnectionName($name);
    }
}
