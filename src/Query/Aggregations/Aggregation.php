<?php

namespace AviationCode\Elasticsearch\Query\Aggregations;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\DateHistogram;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @method self terms(string $key, string $field, array $options = [])
 * @method self dateHistogram(string $key, string $field, string $interval, string $intervalType = DateHistogram::FIXED, array $options = [])
 * @method self dateRange(string $key, string $field, array $ranges, array $options = [])
 * @method self cardinality(string $key, string $field, array $options = [])
 * @method self min(string $key, string $field, array $options = [])
 * @method self max(string $key, string $field, array $options = [])
 * @method self stats(string $key, string $field, array $options = [])
 * @method self extendedStats(string $key, string $field, array $options = [])
 * @method self avg(string $key, string $field, array $options = [])
 * @method self weightedAvg(string $key, array $value, array $weight)
 * @method self geoBounds(string $key, string $field, array $options = [])
 * @method self geoCentroid(string $key, string $field)
 * @method self sum(string $key, string $field, array $options = [])
 * @method self valueCount(string $key, string $field)
 * @method mixed get(string $key, $default = null)
 * @method mixed first(string $key, $default = null)
 * @method bool has(string $key)
 * @method void put(string $key, $value)
 * @method int count()
 * @method array toArray()
 */
class Aggregation
{
    /**
     * List of namespaces to search aggregations in.
     *
     * @var array
     */
    private static $namespaces = [
        '\AviationCode\Elasticsearch\Query\Aggregations\Metric',
        '\AviationCode\Elasticsearch\Query\Aggregations\Bucket',
        '\AviationCode\Elasticsearch\Query\Aggregations\Pipeline',
        '\AviationCode\Elasticsearch\Query\Aggregations\Matrix',
    ];

    /**
     * Internal list of aggregations.
     *
     * @var Collection
     */
    protected $aggregations;

    /**
     * Aggregation constructor.
     */
    public function __construct()
    {
        $this->aggregations = new Collection();
    }

    /**
     * Add an aggregation.
     *
     * @param string $class
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    private function addAggregation(string $class, string $method, array $arguments): self
    {
        if (!isset($arguments[0])) {
            throw new \InvalidArgumentException('Missing argument $key.');
        }

        // Fetch the key out of the argument list
        $key = $arguments[0];

        // Split key into different parts
        $parts = collect(explode('.', $key));

        // Remove the key from the arguments list by shifting it out of the array.
        array_shift($arguments);

        // When we have multiple key elements we have to delegate to next aggregation level.
        if ($parts->count() > 1) {
            if (!$this->has($parts->first())) {
                throw new \InvalidArgumentException(
                    'Cannot define nested aggregation before defining parent aggregation.'
                );
            }

            $this->get($parts->first())->$method(implode('.', $parts->except(0)->toArray()), ...$arguments);

            return $this;
        }

        $this->put($parts->first(), new $class(...$arguments));

        return $this;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        $class = Str::studly($method);

        foreach (static::$namespaces as $namespace) {
            $fqn = "$namespace\\$class";

            if (class_exists($fqn)) {
                return $this->addAggregation($fqn, $method, $arguments);
            }
        }

        return $this->aggregations->$method(...$arguments);
    }
}
