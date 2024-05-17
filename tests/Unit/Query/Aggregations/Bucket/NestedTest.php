<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Nested;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class NestedTest extends TestCase
{
    #[Test]
    public function it_adds_nested_aggregation(): void
    {
        $nested = new Nested('resellers');

        $this->assertEquals(['nested' => ['path' => 'resellers']], $nested->toArray());
    }
}
