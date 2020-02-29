<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;

class SerialDiff
{
    use HasAggregations;

    public const SKIP = 'skip';
    public const INSERT_ZEROS = 'insert_zeros';


    /**
     * This path to the buckets we wish to perform bucket aggregation on.
     *
     * @var string
     */
    private $bucket;

    /**
     * The policy to apply when gaps are found in the data.
     *
     * @var string|null
     */
    private $gap;

    /**
     * The historical bucket to subtract from the current value.
     *
     * @var int|null
     */
    private $lag;

    /**
     * Format to apply to the output value of this aggregation.
     *
     * @var string|null
     */
    private $format;

    /**
     * SerialDiff constructor.
     *
     * @param string $bucket
     * @param int|null $lag
     * @param string|null $format
     * @param string|null $gap
     */
    public function __construct(string $bucket, ?int $lag = null, ?string $format = null, ?string $gap = null)
    {
        $this->key = 'serial_diff';
        $this->aggregations = new Aggregation();

        $this->bucket = $bucket;
        $this->gap = $gap;
        $this->lag = $lag;
        $this->format = $format;
    }

    protected function toElastic(): array
    {
        $params = [
            'buckets_path' => $this->bucket,
        ];

        if ($this->gap) {
            $params['gap_policy'] = $this->gap;
        }

        if ($this->lag) {
            $params['lag'] = $this->lag;
        }

        if ($this->format) {
            $params['format'] = $this->format;
        }

        return $params;
    }
}
