<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class Composite extends Bucket
{
    /** @var array */
    protected $sources;

    /** @var array */
    protected $options = [];

    protected $allowedAggregations = [
        Terms::class,
        Histogram::class,
        DateHistogram::class,
        GeotileGrid::class,
    ];

    protected $allowedOptions = [
        'size',
        'after',
    ];

    public function __construct(array $sources = [], array $options = [])
    {
        parent::__construct('composite');

        $this->sources = $sources;
        $this->options = $options;
    }

    public function addTermsSource(string $key, Terms $terms): self
    {
        $this->sources[$key] = $terms;

        return $this;
    }

    public function addHistogramSource(string $key, Histogram $histogram): self
    {
        $this->sources[$key] = $histogram;

        return $this;
    }

    public function addDateHistogramSource(string $key, DateHistogram $dateHistogram): self
    {
        $this->sources[$key] = $dateHistogram;

        return $this;
    }

    public function addGeoTileGridSource(string $key, GeotileGrid $geoTileGrid): self
    {
        $this->sources[$key] = $geoTileGrid;

        return $this;
    }

    protected function toElastic(): array
    {
        $mappedSources = [];
        foreach ($this->sources as $key => $aggregation) {
            if (!$aggregation instanceof Bucket) {
                continue;
            }

            if (!in_array(get_class($aggregation), $this->allowedAggregations)) {
                continue;
            }

            if (!is_string($key)) {
                continue;
            }

            $mappedSources[] = [$key => $aggregation->toArray()];
        }

        if (!count($mappedSources)) {
            throw new \InvalidArgumentException('No valid sources were provided.');
        }

        return array_merge(
            ['sources' => $mappedSources],
            $this->allowedOptions($this->options)
        );
    }
}
