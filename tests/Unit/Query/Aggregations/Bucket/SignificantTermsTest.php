<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\SignificantTerms;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class SignificantTermsTest extends TestCase
{
    #[Test]
    public function it_builds_a_significant_terms_aggregation()
    {
        $terms = new SignificantTerms('crime_type');

        $this->assertEquals([
            'significant_terms' => [
                'field' => 'crime_type',
            ]
        ], $terms->toArray());
    }

    #[Test]
    public function it_builds_a_significant_terms_aggregation_with_options()
    {
        $terms = new SignificantTerms('crime_type', [
            'jlh' => [],
            'include_negatives' => true,
            'background_is_superset' => false,
            'chi_square' => [],
            'gnd' => [],
            'percentage' => [],
            'script' => ['lang' => 'painless'],
            'shard_size' => 1,
            'min_doc_count' => 10,
            'shard_min_doc_count' => 1,
            'background_filter' => ['term' => ['text' => 'spain']],
            'execution_hint' => 'map',
        ]);

        $this->assertEquals([
            'significant_terms' => [
                'field' => 'crime_type',
                'jlh' => [],
                'include_negatives' => true,
                'background_is_superset' => false,
                'chi_square' => [],
                'gnd' => [],
                'percentage' => [],
                'script' => ['lang' => 'painless'],
                'shard_size' => 1,
                'min_doc_count' => 10,
                'shard_min_doc_count' => 1,
                'background_filter' => ['term' => ['text' => 'spain']],
                'execution_hint' => 'map',
            ]
        ], $terms->toArray());
    }
    
    #[Test]
    public function it_builds_a_significant_terms_aggregation_with_invalid_options()
    {
        $terms = new SignificantTerms('crime_type', [
            'invalid_option' => 'test',
        ]);

        $this->assertEquals([
            'significant_terms' => [
                'field' => 'crime_type',
            ]
        ], $terms->toArray());
    }
}
