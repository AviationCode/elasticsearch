<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use Illuminate\Support\Fluent;

/**
 * Class Stats.
 *
 * @property int|float $count;
 * @property int|float $min;
 * @property int|float $max;
 * @property int|float $avg;
 * @property int|float $sum;
 */
class Stats extends Fluent
{
}
