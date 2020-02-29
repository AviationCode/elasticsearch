<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Common;

use AviationCode\Elasticsearch\Model\Aggregations\Common\Item;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ItemTest extends TestCase
{
    /** @test * */
    public function it_maps_key_values()
    {
        $item = new Item([
            'key' => 'value',
        ]);

        $this->assertEquals('value', $item->key);
    }

    /** @test * */
    public function it_maps_nested_elements()
    {
        $item = new Item([
            'key' => 'jeffreyway',
            'doc_count' => 50,
            'date_histogram#tweets_per_day' => [
                'buckets' => [
                    [
                        'key_as_string' => '2019-12-08 00:00:00',
                        'key' => 1575763200000,
                        'doc_count' => 13,
                    ],
                    [
                        'key_as_string' => '2019-12-09 00:00:00',
                        'key' => 1575849600000,
                        'doc_count' => 37,
                    ],
                ],
            ],
        ]);

        $this->assertEquals('jeffreyway', $item->key);
        $this->assertEquals(50, $item->doc_count);
        $this->assertEquals(13, $item->tweets_per_day[0]->doc_count);
        $this->assertEquals(37, $item->tweets_per_day[1]->doc_count);
    }

    /** @test **/
    public function it_maps_nested_simple_values_to_the_key()
    {
        $item = new Item([
            'key' => 'jeffreyway',
            'doc_count' => 50,
            'sum#total_tweets' => [
                'value' => 1000
            ],
        ]);

        $this->assertEquals('jeffreyway', $item->key);
        $this->assertEquals(50, $item->doc_count);
        $this->assertEquals(1000, $item->total_tweets);
    }
}
