<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class TermsTest extends TestCase
{
    /** @test **/
    public function it_builds_a_terms_aggregation()
    {
        $agg = new Aggregation();
        $agg->terms('test_1', 'user');
        $agg->terms('test_2', 'user', ['size' => 5]);
        $agg->terms('test_3', 'user', ['show_term_doc_count_error' => true]);
        $agg->terms('test_4', 'user', ['order' => ['_key' => 'desc']]);
        $agg->terms('test_5', 'user', ['min_doc_count' => 5]);
        $agg->terms('test_6', 'user', ['include' => '.*sport.*']);
        $agg->terms('test_7', 'user', ['include' => ['elonmusk', 'jeffreyway']]);
        $agg->terms('test_8', 'user', ['exclude' => 'water_.*']);
        $agg->terms('test_9', 'user', ['exclude' => ['elonmusk', 'jeffreyway']]);
        $agg->terms('test_10', 'user', ['missing' => 'others']);
        $agg->terms('test_11', 'user', ['invalid_key' => 'others']);

        $this->assertEquals([
            'test_1'  => ['terms' => ['field' => 'user']],
            'test_2'  => ['terms' => ['field' => 'user', 'size' => 5]],
            'test_3'  => ['terms' => ['field' => 'user', 'show_term_doc_count_error' => true]],
            'test_4'  => ['terms' => ['field' => 'user', 'order' => ['_key' => 'desc']]],
            'test_5'  => ['terms' => ['field' => 'user', 'min_doc_count' => 5]],
            'test_6'  => ['terms' => ['field' => 'user', 'include' => '.*sport.*']],
            'test_7'  => ['terms' => ['field' => 'user', 'include' => ['elonmusk', 'jeffreyway']]],
            'test_8'  => ['terms' => ['field' => 'user', 'exclude' => 'water_.*']],
            'test_9'  => ['terms' => ['field' => 'user', 'exclude' => ['elonmusk', 'jeffreyway']]],
            'test_10' => ['terms' => ['field' => 'user', 'missing' => 'others']],
            'test_11' => ['terms' => ['field' => 'user']],
        ], $agg->toArray());
    }
}
