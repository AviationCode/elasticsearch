<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Children;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ChildrenTest extends TestCase
{
    #[Test]
    public function it_adds_parent_aggregation(): void
    {
        $children = new Children('answer');

        $this->assertEquals(['children' => ['type' => 'answer']], $children->toArray());
    }
}
