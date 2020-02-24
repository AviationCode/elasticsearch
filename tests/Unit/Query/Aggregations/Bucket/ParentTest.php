<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\ParentBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ParentTest extends TestCase
{
    /** @test **/
    public function it_adds_parent_aggregation()
    {
        $missing = new ParentBucket('answer');

        $this->assertEquals(['parent' => ['type' => 'answer']], $missing->toArray());
    }
}
