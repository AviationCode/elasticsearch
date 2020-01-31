<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations;

use AviationCode\Elasticsearch\Model\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class AggregationTest extends TestCase
{
    /** @test * */
    public function it_handles_special_string_terms_model()
    {
        $aggregations = new Aggregation([
            'sterms#genres' => [
                'doc_count_error_upper_bound' => 0,
                'sum_other_doc_count' => 0,
                'buckets' => [
                    [
                        'key' => 'electronic',
                        'doc_count' => 6
                    ],
                    [
                        'key' => 'rock',
                        'doc_count' => 3
                    ],
                    [
                        'key' => 'jazz',
                        'doc_count' => 2
                    ]
                ]
            ]
        ]);

        $this->assertSame(0, $aggregations->genres->doc_count_error_upper_bound);
        $this->assertSame(0, $aggregations->genres->sum_other_doc_count);
        $this->assertSame('electronic', $aggregations->genres[0]->key);
        $this->assertSame(6, $aggregations->genres[0]->doc_count);
        $this->assertSame('rock', $aggregations->genres->get(1)->key);
        $this->assertSame(3, $aggregations->genres->get(1)->doc_count);
        $this->assertSame('jazz', $aggregations->genres[2]->key);
        $this->assertSame(2, $aggregations->genres[2]->doc_count);
    }

    /** @test * */
    public function it_handles_special_long_terms_model()
    {
        $aggregations = new Aggregation([
            'lterms#genres' => [
                'doc_count_error_upper_bound' => 0,
                'sum_other_doc_count' => 0,
                'buckets' => [
                    [
                        'key' => 1,
                        'doc_count' => 6
                    ],
                    [
                        'key' => 2,
                        'doc_count' => 3
                    ],
                    [
                        'key' => 3,
                        'doc_count' => 2
                    ]
                ]
            ]
        ]);

        $this->assertSame(0, $aggregations->genres->doc_count_error_upper_bound);
        $this->assertSame(0, $aggregations->genres->sum_other_doc_count);
        $this->assertSame(1, $aggregations->genres[0]->key);
        $this->assertSame(6, $aggregations->genres[0]->doc_count);
        $this->assertSame(2, $aggregations->genres->get(1)->key);
        $this->assertSame(3, $aggregations->genres->get(1)->doc_count);
        $this->assertSame(3, $aggregations->genres[2]->key);
        $this->assertSame(2, $aggregations->genres[2]->doc_count);
    }
}
