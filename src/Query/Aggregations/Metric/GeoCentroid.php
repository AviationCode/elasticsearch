<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\GeoCentroid as GeoCentroidModel;

class GeoCentroid extends Metric
{
    /**
     * @var string
     */
    private $field;

    public function __construct(string $field)
    {
        parent::__construct('geo_centroid', GeoCentroidModel::class);

        $this->field = $field;
    }

    /**
     * {@inheritdoc}
     */
    protected function toElastic(): array
    {
        return ['field' => $this->field];
    }
}
