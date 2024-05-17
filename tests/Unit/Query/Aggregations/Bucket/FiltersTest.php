<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Filters;
use AviationCode\Elasticsearch\Query\Dsl\Term\Term;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class FiltersTest extends TestCase
{
    #[Test]
    public function it_adds_filters_aggregation(): void
    {
        $filters = new Filters([
            't-shirt' => new Term('type', 't-shirt'),
            'hat' => new Term('type', 'hat'),
        ]);

        $this->assertEquals([
            'filters' => [
                'filters' => [
                    't-shirt' => ['term' => ['type' => ['value' => 't-shirt']]],
                    'hat' => ['term' => ['type' => ['value' => 'hat']]],
                ],
            ],
        ], $filters->toArray());
    }

    #[Test]
    public function it_adds_filters_aggregation_must_have_at_least_one_filters(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Filters([]);
    }

    #[Test]
    public function it_adds_filters_aggregation_with_options(): void
    {
        $filters = new Filters([
            't-shirt' => new Term('type', 't-shirt'),
            'hat' => new Term('type', 'hat'),
        ], 'other_types');

        $this->assertEquals([
            'filters' => [
                'filters' => [
                    't-shirt' => ['term' => ['type' => ['value' => 't-shirt']]],
                    'hat' => ['term' => ['type' => ['value' => 'hat']]],
                ],
                'other_bucket_key' => 'other_types',
            ],
        ], $filters->toArray());
    }
}
