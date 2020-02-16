<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class GeoDistance extends Bucket
{
    public const MI = 'mi';
    public const YD = 'yd';
    public const FT = 'ft';
    public const IN = 'in';
    public const KM = 'km';
    public const M = 'm';
    public const CM = 'cm';
    public const MM = 'mm';
    public const NM = 'NM';

    /**
     * The field you wish to search.
     *
     * @var string
     */
    private $field;

    /**
     * @var float
     */
    private $lat;

    /**
     * @var float
     */
    private $lon;

    /**
     * @var string
     */
    private $unit = self::M;

    /**
     * @var array
     */
    private $ranges = [];

    /**
     * GeoDistance constructor.
     */
    public function __construct(string $field, float $lat, float $lon, $ranges = [], ?string $unit = null)
    {
        parent::__construct('geo_distance', null);

        $this->field = $field;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->unit = $unit ?? self::M;

        if (is_string($ranges)) {
            $this->unit = $ranges;
        } else {
            $this->ranges = $ranges;
        }
    }


    /**
     * @param int|float $to
     * @param string|null $key
     *
     * @return $this
     */
    public function to($to, ?string $key = null): self
    {
        $this->ranges[] = ['to' => $to] + array_filter(['key' => $key]);

        return $this;
    }

    /**
     * @param int|float $from
     * @param int|float $to
     * @param string|null $key
     *
     * @return $this
     */
    public function range($from, $to, ?string $key = null): self
    {
        $this->ranges[] = ['from' => $from, 'to' => $to] + array_filter(['key' => $key]);

        return $this;
    }

    /**
     * @param float $from
     * @param string|null $key
     *
     * @return $this
     */
    public function from($from, ?string $key = null): self
    {
        $this->ranges[] = ['from' => $from] + array_filter(['key' => $key]);

        return $this;
    }
    
    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        return [
            'field' => $this->field,
            'keyed' => true,
            'origin' => ['lat' => $this->lat, 'lon' => $this->lon],
            'ranges' => $this->ranges,
            'unit' => $this->unit,
        ];
    }
}
