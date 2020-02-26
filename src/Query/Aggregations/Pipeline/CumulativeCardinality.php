<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class CumulativeCardinality
 */
class CumulativeCardinality implements Arrayable
{
    use HasAggregations;

    public const GAP_SKIP = 'skip';
    public const GAP_INSERT_ZEROS = 'insert_zeros';

    /**
     * This path to the buckets we wish to perform bucket aggregation on.
     *
     * @var array|string
     */
    private $buckets;

    /**
     * format to apply to the output value of this aggregation.
     *
     * @var string|null
     */
    private $format;

    /**
     * CumulativeCardinality constructor.
     *
     * @param array|string $buckets
     * @param string|null $format
     */
    public function __construct($buckets, string $format = null)
    {
        $this->key = 'cumulative_cardinality';
        $this->aggregations = new Aggregation();

        $this->buckets = $buckets;
        $this->format = $format;
    }

    protected function toElastic(): array
    {
        $params = ['buckets_path' => $this->buckets];

        if ($this->format) {
            $params['format'] = $this->format;
        }

        return $params;
    }
}
