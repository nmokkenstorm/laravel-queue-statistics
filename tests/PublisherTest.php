<?php

namespace Nmokkenstorm\LaravelQueueStatistics\Tests;

use Carbon\Carbon;

use Nmokkenstorm\LaravelQueueStatistics\Publisher;
use Nmokkenstorm\LaravelQueueStatistics\Contracts\FlushStrategy;
use Nmokkenstorm\LaravelQueueStatistics\Contracts\PublishesQueueStatistics;

class PublisherTest extends TestCase
{
    /**
     * Hook into the testing famework 
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->flushStrategy  = \Mockery::mock(FlushStrategy::class);
        $this->publishBackend = \Mockery::mock(PublishesQueueStatistics::class);

        $this->parameters = [
                'id'        => 'fake-id',
                'event'     => 'fake-event',
                'job'       => 'fake-job',
                'queue'     => 'fake-queue',
                'timestamp' => Carbon::now()->timestamp
        ];
    }

    /**
     * @test
     */
    public function publish_to_a_backend()
    {
        $this->flushStrategy
             ->shouldReceive('shouldFlush')
             ->once()
             ->with([$this->parameters])
             ->andReturn(true);

        $this->publishBackend
             ->shouldReceive('flush')
             ->once()
             ->with([$this->parameters]);

        $publisher = new Publisher($this->flushStrategy, $this->publishBackend);

        $publisher->publish($this->parameters['id'], $this->parameters['job'], $this->parameters['event'], $this->parameters['queue']);
    }
}
