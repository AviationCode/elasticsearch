<?php

namespace AviationCode\Elasticsearch\Query\Aggregations;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\DateHistogram;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @method self terms(string $key, string $field, array $options = [])
 * @method self dateHistogram(string $key, string $field, string $interval, string $intervalType = DateHistogram::FIXED, array $options = [])
 * @method self valueCount(string $key, string $field)
 */
class Aggregation extends Collection
{
    private static $namespaces = [
        '\AviationCode\Elasticsearch\Query\Aggregations\Metric',
        '\AviationCode\Elasticsearch\Query\Aggregations\Bucket',
        '\AviationCode\Elasticsearch\Query\Aggregations\Pipeline',
        '\AviationCode\Elasticsearch\Query\Aggregations\Matrix',
    ];

    /**
     * Add an aggregation.
     *
     * @param $class
     * @param string $method
     * @param $arguments
     *
     * @return $this
     */
    private function addAggregation($class, string $method, $arguments): self
    {
        if (! isset($arguments[0])) {
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
            if (! $this->has($parts->first())) {
                throw new \InvalidArgumentException('Cannot define nested aggregation before defining parent aggregation.');
            }

            $this->get($parts->first())->$method($parts->except(0)->join('.'), ...$arguments);

            return $this;
        }

        $this->put($parts->first(), new $class(...$arguments));

        return $this;
    }

    /**
     * {@inheritdoc}
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

        return parent::__call($method, $arguments);
    }
}
