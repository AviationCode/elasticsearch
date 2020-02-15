<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Geo;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

class GeoDistance implements Arrayable
{
    private const KEY = 'geo_distance';

    public const MI = 'mi';
    public const YD = 'yd';
    public const FT = 'ft';
    public const IN = 'in';
    public const KM = 'km';
    public const M = 'm';
    public const CM = 'cm';
    public const MM = 'mm';
    public const NM = 'NM';

    public const PLANE = 'plane';
    public const ARC = 'arc';

    public const IGNORE_MALFORMED = 'ignore_malformed';
    public const STRICT = 'strict';

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
     * @param string                   $field
     * @param array|string|float|float $lat
     * @param array|string|float|float $lon
     * @param array|string|null        $distance
     * @param array|null               $unit
     * @param array|null               $options
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

            if (is_string($lon) || is_numeric($lon)) {
                $distance = (string) $lon;
            }
        } else {
            $this->point = ['lat' => $lat, 'lon' => $lon];

            if ($options === null && is_array($unit)) {
                $options = $unit;
            }
        }

        if ($distance === null || is_array($distance)) {
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
                'distance'   => $this->distance,
                $this->field => $this->point,
            ], $this->options),
        ];
    }
}
