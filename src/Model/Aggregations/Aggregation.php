<?php

namespace AviationCode\Elasticsearch\Model\Aggregations;

use AviationCode\Elasticsearch\Helpers\HasAttributes;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation as AggregationQuery;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;

class Aggregation
{
    use HasAttributes;

    /**
     * Aggregation constructor.
     * @param array $aggregations
     * @param AggregationQuery|HasAggregations $query
     */
    public function __construct(array $aggregations, $query)
    {
        if ($query instanceof AggregationQuery) {
            foreach ($aggregations as $key => $value) {
                $qb = $query->get($key);

                $this->$key = $qb->newModel($value, $qb, $key);
            }

            return;
        }
    }
}
