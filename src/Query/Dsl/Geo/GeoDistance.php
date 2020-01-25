<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Geo;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

class GeoDistance implements Arrayable
{
    private const KEY = 'geo_distance';

    const MI = 'mi';
    const YD = 'yd';
    const FT = 'ft';
    const IN = 'in';
    const KM = 'km';
    const M = 'm';
    const CM = 'cm';
    const MM = 'mm';
    const NM = 'NM';

    const PLANE = 'plane';
    const ARC = 'arc';

    const IGNORE_MALFORMED = 'ignore_malformed';
    const STRICT = 'strict';

    /**
     * @var string
     */
    private $field;

    /**
     * @var mixed
     */
    private $point;

    /**
     * @var string
     */
    private $distance;

    /**
     * @var array
     */
    private $options = [];

    /**
     * GeoDistance constructor.
     *
     * @param string $field
     * @param array|string|double|float$lat
     * @param array|string|double|float $lon
     * @param null $distance
     * @param null $unit
     * @param array|null $options
     */
    public function __construct(string $field, $lat, $lon, $distance = null, $unit = null, ?array $options = null)
    {
        $this->field = $field;

        if (is_array($lat) || is_string($lat)) {
            $this->point = $lat;

            if (is_array($distance)) {
                $options = $distance;
            } else {
                $options = $unit;
                $unit = $distance;
            }

            $distance = $lon;
        } else {
            $this->point = ['lat' => $lat, 'lon' => $lon];

            if ($options === null && is_array($unit)) {
                $options = $unit;
            }
        }

        if ($distance === null) {
            throw new InvalidArgumentException('Distance is required');
        }

        $this->distance = $distance;

        if (is_string($unit)) {
            $this->distance .= $unit;
        }

        $this->options = $options ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            static::KEY => array_merge([
                'distance' => $this->distance,
                $this->field => $this->point,
            ], $this->options),
        ];
    }
}
