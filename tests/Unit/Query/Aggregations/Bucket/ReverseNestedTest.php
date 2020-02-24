<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\ReverseNested;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ReverseNestedTest extends TestCase
{
    /** @test **/
    public function it_adds_reverse_nested_aggregation()
    {
        $nested = new ReverseNested('issues');

        $this->assertEquals(['reverse_nested' => ['path' => 'issues']], $nested->toArray());
    }
}
