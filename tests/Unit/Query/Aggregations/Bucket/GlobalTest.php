<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\GlobalBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GlobalTest extends TestCase
{
    /** @test **/
    public function it_builds_a_global_aggregation()
    {
        $global = new GlobalBucket();

        $this->assertEquals(['global' => new \stdClass()], $global->toArray());
    }
}
