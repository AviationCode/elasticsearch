<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Bucket;

use AviationCode\Elasticsearch\Model\Aggregations\Bucket\Filters;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class FiltersTest extends TestCase
{
    /** @test */
    public function it_successfully_translates_filters_aggregation()
    {
        $filters = new Filters([
            'buckets' => [
                'errors' => ['doc_count' => 1],
                'warnings' => ['doc_count' => 2],
            ],
        ]);

        $buckets = array_values($filters->all());

        $this->assertCount(2, $filters);
        $this->assertEquals(1, $buckets[0]->doc_count);
        $this->assertEquals(2, $buckets[1]->doc_count);
    }

    /** @test */
    public function it_successfully_translates_anonymous_filters()
    {
        $filters = new Filters([
            'buckets' => [
                ['doc_count' => 1],
                ['doc_count' => 2],
            ],
        ]);

        $this->assertCount(2, $filters);
        $this->assertEquals(1, $filters->first()->doc_count);
        $this->assertEquals(2, $filters->last()->doc_count);
    }
}
