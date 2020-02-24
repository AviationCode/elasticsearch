<?php

namespace AviationCode\Elasticsearch\Model\Aggregations;

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
        '\AviationCode\Elasticsearch\Model\Aggregations\Pipeline',
        '\AviationCode\Elasticsearch\Model\Aggregations\Matrix',
    ];

    /**
     * List of special typed keys which do not directly related to their aggregation type.
     *
     * @var array
     */
    public static $specialTypes = [
        'sterms' => 'terms',
        'lterms' => 'terms',
        'sigsterms' => 'significant_terms',
        'tdigest_percentiles' => 'percentiles',
        'tdigest_percentile_ranks' => 'percentiles',
        'median_absolute_deviation' => 'simple_value',
        'filter' => 'bucket_item',
        'global' => 'global_bucket',
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

            return [$key => new $fqn($value)];
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
