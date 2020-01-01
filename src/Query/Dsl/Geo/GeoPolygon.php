<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Geo;

use Illuminate\Contracts\Support\Arrayable;

class GeoPolygon implements Arrayable
{
    private const KEY = 'geo_polygon';

    const STRICT = 'strict';
    const IGNORE_MALFORMED = 'ignore_malformed';
    const COERCE = 'coerce';

    /**
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $points;

    /**
     * @var string|null
     */
    private $validationMethod;

    /**
     * @var bool
     */
    private $unmapped;

    /**
     * GeoPolygon constructor.
     *
     * @param string $field
     * @param array $points
     * @param string|null $validationMethod
     * @param bool $unmapped
     */
    public function __construct(string $field, array $points, ?string $validationMethod = null, bool $unmapped = false)
    {
        $this->field = $field;
        $this->points = $points;
        $this->validationMethod = $validationMethod;
        $this->unmapped = $unmapped;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $params = ['points' => $this->points];

        if ($this->validationMethod) {
            $params['validation_method'] = $this->validationMethod;
        }

        if ($this->unmapped) {
            $params['ignore_unmapped'] = true;
        }

        return [static::KEY => [$this->field => $params]];
    }
}
