<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Derivative
 */
class Derivative implements Arrayable
{
    use HasAggregations;

    const GAP_SKIP = 'skip';
    const GAP_INSERT_ZEROS = 'insert_zeros';

    /**
     * This path to the buckets we wish to find the derivative.
     *
     * @var array|string
     */
    private $buckets;

    /**
     * The policy to apply when gaps are found in the data.
     *
     * @var string|null
     */
    private $gap;

    /**
     * format to apply to the output value of this aggregation.
     *
     * @var string|null
     */
    private $format;

    /**
     * The derivative aggregation allows the units of the derivative values to be specified.
     * This returns an extra field in the response `normalized_value`,
     * which reports the derivative value in the desired x-axis unit.
     *
     * @var string|null
     */
    private $unit;

    /**
     * Derivative constructor.
     *
     * @param array|string $buckets
     * @param string|null $gap
     * @param string|null $format
     * @param string|null $unit
     */
    public function __construct($buckets, ?string $gap = null, ?string $format = null, ?string $unit = null)
    {
        $this->key = 'derivative';
        $this->aggregations = new Aggregation();

        $this->buckets = $buckets;
        $this->gap = $gap;
        $this->format = $format;
        $this->unit = $unit;
    }

    protected function toElastic(): array
    {
        $params = ['buckets_path' => $this->buckets];

        if ($this->gap) {
            $params['gap_policy'] = $this->gap;
        }

        if ($this->format) {
            $params['format'] = $this->format;
        }

        if ($this->unit) {
            $params['unit'] = $this->unit;
        }

        return $params;
    }
}
