<?php

namespace AviationCode\Elasticsearch\Model\Aggregations;

use AviationCode\Elasticsearch\Model\Aggregations\Common\SimpleValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;

class Aggregation extends Fluent
{
    /**
     * List of namespaces to search Aggregations in.
     *
     * @var array
     */
    public static $namespaces = [
        '\AviationCode\Elasticsearch\Model\Aggregations\Common',
        '\AviationCode\Elasticsearch\Model\Aggregations\Metric',
        '\AviationCode\Elasticsearch\Model\Aggregations\Bucket',
    ];

    /**
     * List of special typed keys which do not directly related to their aggregation type.
     *
     * @var array
     */
    public static $specialTypes = [
        'adjacency_matrix' => 'bucket',
        'avg' => 'simple_value',
        'bucket_metric_value' => 'item',
        'cardinality' => 'simple_value',
        'children' => 'bucket',
        'derivative' => 'item',
        'extended_stats' => 'item',
        'extended_stats_bucket' => 'item',
        'filter' => 'item',
        'filters' => 'bucket',
        'geo_distance' => 'bucket',
        'geohash_grid' => 'bucket',
        'geotile_grid' => 'bucket',
        'global' => 'item',
        'global_bucket' => 'item',
        'histogram' => 'bucket',
        'ip_range' => 'bucket',
        'lterms' => 'bucket',
        'max' => 'simple_value',
        'median_absolute_deviation' => 'simple_value',
        'min' => 'simple_value',
        'missing' => 'item',
        'moving_average' => 'simple_value',
        'nested' => 'item',
        'parent' => 'parent_bucket',
        'parent_bucket' => 'item',
        'percentiles_bucket' => 'item',
        'range' => 'bucket',
        'rare_terms' => 'bucket',
        'sampler' => 'item',
        'significant_terms' => 'bucket',
        'sigsterms' => 'bucket',
        'simple_long_value' => 'simple_value',
        'stats_bucket' => 'item',
        'sterms' => 'bucket',
        'sum' => 'simple_value',
        'tdigest_percentile_ranks' => 'percentiles',
        'tdigest_percentiles' => 'percentiles',
        'terms' => 'bucket',
        'value_count' => 'simple_value',
        'weighted_avg' => 'simple_value',
    ];

    /**
     * Aggregation constructor.
     * @param array $aggregations
     */
    public function __construct(array $aggregations = [])
    {
        parent::__construct((new Collection($aggregations))->mapWithKeys(function ($value, $typedKey) {
            return static::aggregationModel($typedKey, $value);
        }));
    }

    /**
     * Find and return instance of an aggregation model based on a typed elastic key.
     *
     * @param string $typedKey
     * @param mixed $value
     *
     * @return array list($key, $instance)
     */
    public static function aggregationModel(string $typedKey, $value): array
    {
        [$type, $key] = explode('#', $typedKey);

        $type = static::convertSpecialTypes($type);

        $class = Str::studly($type);

        foreach (static::$namespaces as $namespace) {
            $fqn = "$namespace\\$class";

            if (!class_exists($fqn)) {
                continue;
            }

            $model = new $fqn($value);

            // If the model is a simple value let's directly map the value
            // Onto the object this allows the key to equal the value
            // instead having to use $item->value
            if ($model instanceof SimpleValue) {
                $model = $model->value;
            }

            return [$key => $model];
        }

        throw new \InvalidArgumentException(
            "$class does not exist in any of the \AviationCode\Elasticsearch\Model\Aggregations"
        );
    }

    private static function convertSpecialTypes(string $type): string
    {
        if (isset(static::$specialTypes[$type])) {
            return static::$specialTypes[$type];
        }

        return $type;
    }
}
