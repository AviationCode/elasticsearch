<?php

namespace AviationCode\Elasticsearch\Model\Aggregations;

use AviationCode\Elasticsearch\Helpers\HasAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class Aggregation implements \JsonSerializable, Arrayable
{
    use HasAttributes;

    public static $namespaces = [
        '\AviationCode\Elasticsearch\Model\Aggregations\Metric',
        '\AviationCode\Elasticsearch\Model\Aggregations\Bucket',
        '\AviationCode\Elasticsearch\Model\Aggregations\Pipeline',
        '\AviationCode\Elasticsearch\Model\Aggregations\Matrix',
    ];

    /**
     * Aggregation constructor.
     * @param array $aggregations
     */
    public function __construct(array $aggregations)
    {
        foreach ($aggregations as $typedKey => $value) {
            [$key, $instance] = static::aggregationModel($typedKey, $value);

            $this->$key = $instance;
        }
    }

    /**
     * Find and return instance of an aggregation model based on a typed elastic key.
     *
     * @param $typedKey
     * @param $value
     *
     * @return array list($key, $instance)
     */
    public static function aggregationModel($typedKey, $value): array
    {
        [$type, $key] = explode('#', $typedKey);

        $class = Str::studly($type);

        foreach (static::$namespaces as $namespace) {
            $fqn = "$namespace\\$class";

            if (! class_exists($fqn)) {
                continue;
            }

            return [$key, new $fqn($value)];
        }

        throw new \InvalidArgumentException("$class does not exist in any of the \AviationCode\Elasticsearch\Model\Aggregations");
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->jsonSerialize();
    }
}
