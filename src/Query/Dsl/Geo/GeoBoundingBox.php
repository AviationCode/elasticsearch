<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Geo;

use Illuminate\Contracts\Support\Arrayable;

class GeoBoundingBox implements Arrayable
{
    private const KEY = 'geo_bounding_box';

    public const IGNORE_MALFORMED = 'ignore_malformed';
    public const STRICT = 'strict';

    public const INDEXED = 'indexed';
    public const MEMORY = 'memory';

    /**
     * @var string
     */
    private $field;

    /**
     * @var array|string
     */
    private $boundingBox;

    /**
     * @var string
     */
    private $wkt;

    /**
     * @var array
     */
    private $options = [];

    /**
     * GeoBoundingBox constructor.
     *
     * @param string $field
     * @param string|array $topLeft
     * @param null $bottomRight
     * @param array|null $options
     */
    public function __construct(string $field, $topLeft, $bottomRight = null, ?array $options = null)
    {
        $this->field = $field;

        if (is_string($topLeft) && ($bottomRight === null || is_array($bottomRight))) {
            $this->wkt = $topLeft;
            $this->options = $bottomRight ?? [];

            return;
        }

        $this->boundingBox = [
            'top_left' => $topLeft,
            'bottom_right' => $bottomRight,
        ];
        $this->options = $options ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $boundingBox = $this->wkt ? ['wkt' => $this->wkt] : $this->boundingBox;

        return [static::KEY => array_merge([$this->field => $boundingBox], $this->options)];
    }
}
