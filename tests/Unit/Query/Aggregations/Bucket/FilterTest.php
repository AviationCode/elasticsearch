<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Filter;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class FilterTest extends TestCase
{
    #[Test]
    public function it_adds_filter_aggregation(): void
    {
        $filter = new Filter(function ($filter) {
            return $filter->term('type', 't-shirt');
        });

        $this->assertEquals(['filter' => ['term' => ['type' => ['value' => 't-shirt']]]], $filter->toArray());
    }

    #[Test]
    public function it_adds_filter_aggregation_must_have_at_least_one_filter(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Filter(function ($filter) {});
    }

    #[Test]
    public function it_adds_filter_aggregation_cannot_have_multiple_filter(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Filter(function ($filter) {
            return $filter->term('type', 't-shirt')->term('type', 'another-value');
        });
    }
}
