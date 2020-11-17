<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\DateHistogram;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\GeotileGrid;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Histogram;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Terms;
use AviationCode\Elasticsearch\Query\Dsl\Term\Range;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class CompositeTest extends TestCase
{
    /** @test */
    public function it_builds_a_composite_aggregation()
    {
        $agg = new Aggregation();

        $agg->composite('composite_1', ['terms_1' => new Terms('terms_field_1')]);
        $agg->composite('composite_2', ['terms_2' => new Terms('terms_field_2')], ['size' => 2]);
        $agg->composite(
            'composite_3',
            ['terms_3' => new Terms('terms_field_3')],
            ['after' => ['terms_3' => 'after_value']]
        );

        $this->assertEquals(
            [
                'composite_1' => [
                    'composite' => [
                        'sources' => [
                            [
                                'terms_1' => [
                                    'terms' => ['field' => 'terms_field_1'],
                                ],
                            ],
                        ],
                    ],
                ],
                'composite_2' => [
                    'composite' => [
                        'sources' => [
                            [
                                'terms_2' => [
                                    'terms' => ['field' => 'terms_field_2'],
                                ],
                            ],
                        ],
                        'size' => 2,
                    ],
                ],
                'composite_3' => [
                    'composite' => [
                        'sources' => [
                            [
                                'terms_3' => [
                                    'terms' => ['field' => 'terms_field_3'],
                                ],
                            ],
                        ],
                        'after' => [
                            'terms_3' => 'after_value',
                        ],
                    ],
                ],
            ],
            $agg->toArray()
        );
    }

    /** @test */
    public function it_filters_out_invalid_sources()
    {
        $agg = new Aggregation();

        $agg->composite(
            'composite_1',
            [
                'terms1' => new Terms('terms_field_1'),
                'range_1' => new Range('range_field'),
            ]
        );

        $this->assertEquals(
            [
                'composite_1' => [
                    'composite' => [
                        'sources' => [
                            [
                                'terms1' => [
                                    'terms' => ['field' => 'terms_field_1'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $agg->toArray()
        );
    }

    /** @test */
    public function it_can_throw_an_invalid_argument_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Aggregation())->composite('test_composite')->toArray();
    }

    /** @test */
    public function it_can_add_sources_dynamically()
    {
        $agg = new Aggregation();

        $agg
            ->composite('composite_test')
            ->addTermsSource('street_source', new Terms('street_field'))
            ->addHistogramSource(
                'histogram_source',
                new Histogram('histogram_field', 2, ['order' => 'desc'])
            )
            ->addDateHistogramSource(
                'date_histogram_source',
                new DateHistogram('date_histogram_field', '1d')
            )
            ->addGeoTileGridSource(
                'geo_tile_grid_source',
                new GeotileGrid('geo_tile_grid_field')
            );

        $this->assertEquals(
            [
                'composite_test' => [
                    'composite' => [
                        'sources' => [
                            [
                                'street_source' => [
                                    'terms' => [
                                        'field' => 'street_field',
                                    ],
                                ],
                            ],
                            [
                                'histogram_source' => [
                                    'histogram' => [
                                        'field' => 'histogram_field',
                                        'interval' => 2,
                                        'order' => 'desc',
                                    ],
                                ],
                            ],
                            [
                                'date_histogram_source' => [
                                    'date_histogram' => [
                                        'field' => 'date_histogram_field',
                                        'fixed_interval' => '1d',
                                    ],
                                ],
                            ],
                            [
                                'geo_tile_grid_source' => [
                                    'geotile_grid' => [
                                        'field' => 'geo_tile_grid_field',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $agg->toArray()
        );
    }
}
