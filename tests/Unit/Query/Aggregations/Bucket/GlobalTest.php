<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\GlobalBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class GlobalTest extends TestCase
{
    #[Test]
    public function it_builds_a_global_aggregation(): void
    {
        $global = new GlobalBucket();

        $this->assertEquals(['global' => new \stdClass()], $global->toArray());
    }
}
