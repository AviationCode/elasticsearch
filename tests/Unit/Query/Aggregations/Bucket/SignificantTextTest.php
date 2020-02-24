<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\SignificantText;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class SignificantTextTest extends TestCase
{
    /** @test **/
    public function it_builds_significant_text_aggregation()
    {
        $significant = new SignificantText('content');

        $this->assertEquals([
            'significant_text' => [
                'field' => 'content',
            ],
        ], $significant->toArray());
    }

    /** @test **/
    public function it_builds_significant_text_aggregation_with_valid_options()
    {
        $significant = new SignificantText('content', [
            'filter_duplicate_text' => true,
            'source_fields' => ['content', 'title'],
        ]);

        $this->assertEquals([
            'significant_text' => [
                'field' => 'content',
                'filter_duplicate_text' => true,
                'source_fields' => ['content', 'title'],
            ],
        ], $significant->toArray());
    }

    /** @test **/
    public function it_builds_significant_text_aggregation_with_invalid_options()
    {
        $significant = new SignificantText('content', [
            'invalid' => 'option',
        ]);

        $this->assertEquals([
            'significant_text' => [
                'field' => 'content',
            ],
        ], $significant->toArray());
    }
}
