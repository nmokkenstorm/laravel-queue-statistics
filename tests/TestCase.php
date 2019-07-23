<?php

namespace Nmokkenstorm\LaravelQueueStatistics\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Hook into the testing framework
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->addToAssertionCount(
            \Mockery::getContainer()->mockery_getExpectationCount()
        );
    }
}
