<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\TopHits;
use AviationCode\Elasticsearch\Model\ElasticHit;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class TopHitsTest extends TestCase
{
    /** @test * */
    public function it_maps_top_hits_model()
    {
        $topHits = new TopHits([
            'hits' => [
                'total' => [
                    'value' => 3,
                    'relation' => 'eq'
                ],
                'max_score' => null,
                'hits' => [
                    [
                        '_index' => 'sales',
                        '_type' => '_doc',
                        '_id' => 'AVnNBmauCQpcRyxw6ChK',
                        '_source' => [
                            'date' => '2015/03/01 00:00:00',
                            'price' => 200
                        ],
                        'sort' => [
                            1425168000000
                        ],
                        '_score' => null
                    ]
                ]
            ]
        ]);

        $this->assertEquals(1, $topHits->count());
        $this->assertTrue(isset($topHits[0]));
        $this->assertInstanceOf(ElasticHit::class, $topHits[0]);
        $this->assertEquals(200, $topHits[0]->price);
        $this->assertEquals('sales', $topHits[0]->elastic['index']);
    }

    /** @test * */
    public function it_cannot_modify_the_results()
    {
        $topHits = new TopHits([
            'hits' => [
                'total' => [
                    'value' => 3,
                    'relation' => 'eq'
                ],
                'max_score' => null,
                'hits' => [
                    [
                        '_index' => 'sales',
                        '_type' => '_doc',
                        '_id' => 'AVnNBmauCQpcRyxw6ChK',
                        '_source' => [
                            'date' => '2015/03/01 00:00:00',
                            'price' => 200
                        ],
                        'sort' => [
                            1425168000000
                        ],
                        '_score' => null
                    ]
                ]
            ]
        ]);

        $this->expectException(\LogicException::class);
        $topHits[0] = 'value';
    }

    /** @test * */
    public function it_cannot_remove_results()
    {
        $topHits = new TopHits([
            'hits' => [
                'total' => [
                    'value' => 3,
                    'relation' => 'eq'
                ],
                'max_score' => null,
                'hits' => [
                    [
                        '_index' => 'sales',
                        '_type' => '_doc',
                        '_id' => 'AVnNBmauCQpcRyxw6ChK',
                        '_source' => [
                            'date' => '2015/03/01 00:00:00',
                            'price' => 200
                        ],
                        'sort' => [
                            1425168000000
                        ],
                        '_score' => null
                    ]
                ]
            ]
        ]);

        $this->expectException(\LogicException::class);
        unset($topHits[0]);
    }
}
