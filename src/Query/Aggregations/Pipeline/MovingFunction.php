<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

/**
 * Class MovingAverage
 */
class MovingFunction implements Arrayable
{
    use HasAggregations;

    /**
     * This path to the buckets we wish to find the derivative.
     *
     * @var array|string
     */
    private $buckets;

    /**
     * @var int
     */
    private $window;

    /**
     * @var string
     */
    private $script;

    /**
     * @var null|int
     */
    private $shift;


    /**
     * Derivative constructor.
     *
     * @param array|string $buckets
     * @param int $window
     * @param string $script
     * @param int|null $shift
     */
    public function __construct($buckets, int $window, string $script, ?int $shift = null)
    {
        $this->key = 'moving_fn';
        $this->aggregations = new Aggregation();

        $this->buckets = $buckets;
        $this->window = $window;
        $this->script = $script;
        $this->shift = $shift;
    }

    protected function toElastic(): array
    {
        $params = [
            'buckets_path' => $this->buckets,
            'window' => $this->window,
            'script' => $this->script,
        ];

        if ($this->shift) {
            $params['shift'] = $this->shift;
        }

        return $params;
    }
}
