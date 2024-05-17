<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MaxTest extends TestCase
{
    #[Test]
    public function it_builds_a_max_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->max('max_price', 'price');
        $aggs->max('max_grade', 'grade', ['missing' => 75]); // With passing $missing optional parameter

        $this->assertEquals([
            'max_price' => ['max' => ['field' => 'price']],
            'max_grade' => ['max' => ['field' => 'grade', 'missing' => 75]],
        ], $aggs->toArray());
    }
}
