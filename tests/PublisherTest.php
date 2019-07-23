<?php

namespace Nmokkenstorm\LaravelQueueStatistics\Tests;

use Carbon\Carbon;

use Nmokkenstorm\LaravelQueueStatistics\Publisher;
use Nmokkenstorm\LaravelQueueStatistics\Contracts\FlushStrategy;
use Nmokkenstorm\LaravelQueueStatistics\Contracts\PublishesQueueStatistics;

class PublisherTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_flush_correctly()
    {
        $flushStrategy  = \Mockery::mock(FlushStrategy::class);
        $publishBackend = \Mockery::mock(PublishesQueueStatistics::class);

        $parameters = [
            [
                'id'        => 'fake-id',
                'event'     => 'fake-event',
                'job'       => 'fake-job',
                'queue'     => 'fake-queue',
                'timestamp' => Carbon::now()->timestamp
            ]
        ];

        $flushStrategy->shouldReceive('shouldFlush')
                      ->once()
                      ->with($parameters)
                      ->andReturn(true);

        $publishBackend->shouldReceive('flush')
                       ->once()
                       ->with($parameters);

        $publisher = new Publisher($flushStrategy, $publishBackend);

        $publisher->publish($parameters[0]['id'], $parameters[0]['job'], $parameters[0]['event'], $parameters[0]['queue']);

    }
}
