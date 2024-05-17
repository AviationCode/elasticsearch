<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Missing;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MissingTest extends TestCase
{
    #[Test]
    public function it_adds_missing_aggregation()
    {
        $missing = new Missing('price');

        $this->assertEquals(['missing' => ['field' => 'price']], $missing->toArray());
    }
}
