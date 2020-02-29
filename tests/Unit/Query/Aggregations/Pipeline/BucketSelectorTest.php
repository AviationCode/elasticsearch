<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\BucketSelector;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class BucketSelectorTest extends TestCase
{
    /** @test **/
    public function it_builds_bucket_selector_aggregation()
    {
        $bucket = new BucketSelector([
            'my_var1' => 'the_sum',
            'my_var2' => 'the_value_count',
        ], 'params.my_var1 > params.my_var2');

        $this->assertEquals([
            'bucket_selector' => [
                'buckets_path' => [
                    'my_var1' => 'the_sum',
                    'my_var2' => 'the_value_count',
                ],
                'script' => 'params.my_var1 > params.my_var2',
            ],
        ], $bucket->toArray());
    }

    /** @test **/
    public function it_builds_bucket_selector_aggregation_with_gap_policy()
    {
        $bucket = new BucketSelector([
            'my_var1' => 'the_sum',
            'my_var2' => 'the_value_count',
        ], 'params.my_var1 > params.my_var2', BucketSelector::SKIP);

        $this->assertEquals([
            'bucket_selector' => [
                'buckets_path' => [
                    'my_var1' => 'the_sum',
                    'my_var2' => 'the_value_count',
                ],
                'script' => 'params.my_var1 > params.my_var2',
                'gap_policy' => 'skip',
            ],
        ], $bucket->toArray());
    }
}
