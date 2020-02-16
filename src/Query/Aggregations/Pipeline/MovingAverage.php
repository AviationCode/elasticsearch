<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

/**
 * Class MovingAverage
 */
class MovingAverage implements Arrayable
{
    use HasAggregations;

    public const GAP_SKIP = 'skip';
    public const GAP_INSERT_ZEROS = 'insert_zeros';

    public const MODEL_SIMPLE = 'simple';
    public const MODEL_LINEAR = 'linear';
    public const MODEL_EWMA = 'ewma';
    public const MODEL_HOLT = 'holt';
    public const MODEL_HOLT_WINTERS = 'holt_winters';

    /**
     * This path to the buckets we wish to find the derivative.
     *
     * @var array|string
     */
    private $buckets;

    /**
     * @var array
     */
    protected $allowedOptions = [
        'model', 'gap_policy', 'window', 'minimize', 'settings', 'predict'
    ];

    /**
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-movavg-aggregation.html
     *
     * @var array
     */
    private $options = [];

    /**
     * Derivative constructor.
     *
     * @param array|string $buckets
     * @param array $options
     */
    public function __construct($buckets, array $options = [])
    {
        $this->key = 'moving_avg';
        $this->aggregations = new Aggregation();

        $this->buckets = $buckets;
        $this->options = $options;
    }

    protected function toElastic(): array
    {
        return array_merge(['buckets_path' => $this->buckets], Arr::only($this->options, $this->allowedOptions));
    }
}
