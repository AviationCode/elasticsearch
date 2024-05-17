<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\BucketSort;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class BucketSortTest extends TestCase
{
    #[Test]
    public function it_creates_basic_bucket_sort()
    {
        $bucket = new BucketSort();

        $this->assertEquals([
            'bucket_sort' => new \stdClass(),
        ], $bucket->toArray());
    }

    #[Test]
    public function it_creates_basic_bucket_with_sort_options()
    {
        $bucket = new BucketSort([
            'sort_field_1' => 'asc',
            'sort_field_2' => 'desc',
        ]);

        $this->assertEquals([
            'bucket_sort' => [
                'sort' => [
                    ['sort_field_1' => ['order' => 'asc']],
                    ['sort_field_2' => ['order' => 'desc']],
                ],
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_creates_basic_bucket_with_all_options()
    {
        $bucket = new BucketSort([
            'sort_field_1' => 'asc',
            'sort_field_2' => 'desc',
        ], 10, 3, BucketSort::SKIP);

        $this->assertEquals([
            'bucket_sort' => [
                'sort' => [
                    ['sort_field_1' => ['order' => 'asc']],
                    ['sort_field_2' => ['order' => 'desc']],
                ],
                'size' => 10,
                'from' => 3,
                'gap_policy' => 'skip'
            ],
        ], $bucket->toArray());
    }
}
