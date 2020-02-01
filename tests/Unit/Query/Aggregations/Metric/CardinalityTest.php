<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class CardinalityTest extends TestCase
{
    /** @test **/
    public function it_builds_a_cardinality_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->cardinality('type_count', 'type');
        /** With Custom 'precision_threshold' */
        $aggs->cardinality('grade_count', 'grade', ['precision_threshold' => 4000]);
        /**  With 'missing' option */
        $aggs->cardinality('tag_cardinality', 'tag', ['missing' => 'N/A']);

        $this->assertEquals([
            'type_count' => ['cardinality' => ['field' => 'type']],
            'grade_count' => ['cardinality' => ['field' => 'grade', 'precision_threshold' => 4000]],
            'tag_cardinality' => ['cardinality' => ['field' => 'tag', 'missing' => 'N/A']],
        ], $aggs->toArray());
    }
}
