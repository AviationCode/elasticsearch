<?php

namespace AviationCode\Elasticsearch\Query\Aggregations;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket;
use AviationCode\Elasticsearch\Query\Aggregations\Metric;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Metric Aggregations
 * ===================
 * @method Metric\Avg avg(string $key, string $field, array $options = [])
 * @method Metric\WeightedAvg weightedAvg(string $key, array $value, array $weight)
 * @method Metric\Cardinality cardinality(string $key, string $field, array $options = [])
 * @method Metric\ExtendedStats extendedStats(string $key, string $field, array $options = [])
 * @method Metric\GeoBounds geoBounds(string $key, string $field, array $options = [])
 * @method Metric\GeoCentroid geoCentroid(string $key, string $field)
 * @method Metric\Min min(string $key, string $field, array $options = [])
 * @method Metric\Max max(string $key, string $field, array $options = [])
 * @method Metric\Percentiles percentiles(string $key, string $field, array $options = [])
 * @method Metric\PercentileRanks percentileRanks(string $key, string $field, array $values, array $options = [])
 * @method Metric\Stats stats(string $key, string $field, array $options = [])
 * @method Metric\StringStats stringStats(string $key, string $field, array $options = [])
 * @method Metric\Sum sum(string $key, string $field, array $options = [])
 * @method Metric\ValueCount topHits(string $key, array $options = [])
 * @method Metric\ValueCount valueCount(string $key, string $field)
 * @method Metric\MedianAbsoluteDeviation medianAbsoluteDeviation(string $key, string $field, array $options = [])
 *
 * Bucket Aggregations
 * ===================
 * @method Bucket\AdjacencyMatrix adjacencyMatrix(string $key, array $filters = [])
 * @method Bucket\AutoDateHistogram autoDateHistogram(string $key, string $field, array $options = [])
 * @method Bucket\Children children(string $key, string $type)
 * @todo composite
 * @method Bucket\DateHistogram dateHistogram(string $key, string $field, string $interval, string $intervalType = Bucket\DateHistogram::FIXED, array $options = [])
 * @method Bucket\DateRange dateRange(string $key, string $field, array $ranges, array $options = [])
 * @todo diversifiedSampler
 * @method Bucket\Filter filter(string $key, callable $callback)
 * @method Bucket\Filters filters(string $key, array $filters, array $options = [])
 * @method Bucket\GeoDistance geoDistance(string $key, string $field, float $lat, float $lon, $ranges = [], ?string $unit = Bucket\GeoDistance::M)
 * @method Bucket\GeohashGrid geohashGrid(string $key, string $field, array $options = [])
 * @method Bucket\GeotileGrid geotileGrid(string $key, string $field, array $options = [])
 * @method Bucket\GlobalBucket global(string $key)
 * @method Bucket\Histogram histogram(string $key, int $interval, array $options = [])
 * @method Bucket\IpRange ipRange(string $key, array $options = [])
 * @method Bucket\Missing missing(string $key, string $field)
 * @method Bucket\Nested nested(string $key, string $path)
 * @method Bucket\ParentBucket parent(string $key, string $type)
 * @method Bucket\Range range(string $key, string $field, array $ranges)
 * @method Bucket\RareTerms rareTerms(string $key, string $field, array $options = [])
 * @method Bucket\ReverseNested reverseNested(string $key, string $path)
 * @method Bucket\Sampler sampler(string $key, ?int $shardSize = null)
 * @method Bucket\SignificantTerms significantTerms(string $key, string $field, array $options = [])
 * @method Bucket\SignificantText significantText(string $key, string $field, array $options = [])
 * @method Bucket\Terms terms(string $key, string $field, array $options = [])
 * @todo subtletiesOfBucketRange
 *
 *
 * Pipeline Aggregations
 * =====================
 * @todo avgBucket
 * @method Pipeline\Derivative derivative(string $key, $bucket, ?string $gap = null, ?string $format = null, ?string $unit = null)
 * @todo maxBucket
 * @todo minBucket
 * @todo sumBucket
 * @todo statsBucket
 * @todo extendedStatsBucket
 * @todo percentilesBucket
 * @method Pipeline\MovingAverage movingAverage(string $key, $buckets, array $options = [])
 * @method Pipeline\MovingFunction movingFunction(string $key, $buckets, int $window, string $script, ?int $shift = null)
 * @todo cumaltiveSum
 * @todo cumaltiveCardinality
 * @todo bucketSelector
 * @todo bucketSort
 * @todo serialDifferencing
 *
 * Aggregation Methods
 * ===================
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
     * Special types / Aliases, These include reserved PHP class names or common aliases.
     *
     * @var array
     */
    private static $specialTypes = [
        'global' => 'global_bucket',
        'parent' => 'parent_bucket',
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
     * @param array $arguments
     *
     * @return HasAggregations
     */
    private function addAggregation(string $class, string $method, array $arguments)
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

            return $this->get($parts->first())->$method(implode('.', $parts->except(0)->toArray()), ...$arguments);
        }

        $this->put($parts->first(), $instance = new $class(...$arguments));

        return $instance;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        $class = Str::studly($this->transformSpecialTypes($method));

        foreach (static::$namespaces as $namespace) {
            $fqn = "$namespace\\$class";

            if (class_exists($fqn)) {
                return $this->addAggregation($fqn, $method, $arguments);
            }
        }

        return $this->aggregations->$method(...$arguments);
    }

    private function transformSpecialTypes(string $method): string
    {
        if (isset(static::$specialTypes[$method])) {
            return static::$specialTypes[$method];
        }

        return $method;
    }
}
