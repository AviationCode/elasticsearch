<?php

namespace AviationCode\Elasticsearch\Tests\Unit;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        \Mockery::close();
    }

    protected function markSuccessfull()
    {
        $this->assertTrue(true);
    }
}
