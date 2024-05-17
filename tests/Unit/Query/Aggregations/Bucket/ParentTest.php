<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\ParentBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class ParentTest extends TestCase
{
    #[Test]
    public function it_adds_parent_aggregation(): void
    {
        $missing = new ParentBucket('answer');

        $this->assertEquals(['parent' => ['type' => 'answer']], $missing->toArray());
    }
}
