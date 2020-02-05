<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class FiltersTest extends TestCase
{
    /** @test */
    public function it_builds_a_filters_bucket_aggregation()
    {
        $aggs = new Aggregation();

        $keyedFilters = [
            'info' => ['match' => ['body' => 'info']],
            'errors' => ['match' => ['body' => 'error']],
            'warnings' => ['match' => ['body' => 'warning']],
        ];

        $anonymousFilters = array_values($keyedFilters);

        $aggs->filters('log_messages', $keyedFilters);
        $aggs->filters('debugging_messages', $anonymousFilters);
        $aggs->filters('messages', $keyedFilters, ['other_bucket' => true]);
        $aggs->filters('dev_mode_messages', $keyedFilters, [
            'other_bucket' => true,
            'other_bucket_key' => 'miscellaneous_messages',
        ]);

        $this->assertEquals([
            'log_messages' => [
                'filters' => [
                    'filters' => [
                        'info' => ['match' => ['body' => 'info']],
                        'errors' => ['match' => ['body' => 'error']],
                        'warnings' => ['match' => ['body' => 'warning']],
                    ],
                ],
            ],
            'debugging_messages' => [
                'filters' => [
                    'filters' => [
                        ['match' => ['body' => 'info']],
                        ['match' => ['body' => 'error']],
                        ['match' => ['body' => 'warning']],
                    ],
                ],
            ],
            'messages' => [
                'filters' => [
                    'filters' => [
                        'info' => ['match' => ['body' => 'info']],
                        'errors' => ['match' => ['body' => 'error']],
                        'warnings' => ['match' => ['body' => 'warning']],
                    ],
                    'other_bucket' => true,
                ],
            ],
            'dev_mode_messages' => [
                'filters' => [
                    'filters' => [
                        'info' => ['match' => ['body' => 'info']],
                        'errors' => ['match' => ['body' => 'error']],
                        'warnings' => ['match' => ['body' => 'warning']],
                    ],
                    'other_bucket' => true,
                    'other_bucket_key' => 'miscellaneous_messages',
                ],
            ],
        ], $aggs->toArray());
    }
}
