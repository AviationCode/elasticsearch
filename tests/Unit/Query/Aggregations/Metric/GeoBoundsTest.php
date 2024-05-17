<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class GeoBoundsTest extends TestCase
{
    #[Test]
    public function it_builds_a_geo_bounds_aggregation(): void
    {
        $aggs = new Aggregation();

        $aggs->geoBounds('viewport', 'location', ['wrap_longitude' => true]);

        $this->assertEquals([
            'viewport' => ['geo_bounds' => ['field' => 'location', 'wrap_longitude' => true]],
        ], $aggs->toArray());
    }
}
