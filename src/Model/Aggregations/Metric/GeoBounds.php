<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Helpers\HasAttributes;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

/**
 * Class GeoBounds.
 *
 * @property array $value;
 */
class GeoBounds implements \JsonSerializable
{
    use HasAttributes;

    /**
     * GeoBounds Aggregation constructor.
     *
     * @param array $value
     */
    public function __construct(array $value)
    {
        $this->topLeft = new Fluent(Arr::get($value, 'bounds.top_left'));

        $this->bottomRight = new Fluent(Arr::get($value, 'bounds.bottom_right'));
    }
}
