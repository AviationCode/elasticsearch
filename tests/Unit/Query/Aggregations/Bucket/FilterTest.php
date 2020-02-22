<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Filter;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class FilterTest extends TestCase
{
    /** @test **/
    public function it_adds_filter_aggregation()
    {
        $filter = new Filter(function ($filter) {
            return $filter->term('type', 't-shirt');
        });

        $this->assertEquals(['filter' => ['term' => ['type' => ['value' => 't-shirt']]]], $filter->toArray());
    }

    /** @test **/
    public function it_adds_filter_aggregation_must_have_at_least_one_filter()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Filter(function ($filter) {});
    }

    /** @test **/
    public function it_adds_filter_aggregation_cannot_have_multiple_filter()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Filter(function ($filter) {
            return $filter->term('type', 't-shirt')->term('type', 'another-value');
        });
    }
}
