<?php

namespace AviationCode\Elasticsearch\Tests\Unit;

use AviationCode\Elasticsearch\Tests\Unit\Schema\IndexTest;
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
