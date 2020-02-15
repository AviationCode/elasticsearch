<?php

namespace AviationCode\Elasticsearch\Model;

use AviationCode\Elasticsearch\Facades\Elasticsearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Trait ElasticSearchable.
 *
 * @mixin Model
 *
 * @property string|null $indexName
 * @property int|null $indexVersion
 * @property array|null $mapping
 */
trait ElasticSearchable
{
    /**
     * Meta information for the current document.
     *
     * @var array
     */
    protected $elastic = [];

    public static function bootElasticSearchable(): void
    {
        static::created(function ($model) {
            /* @var ElasticSearchable $model */
            $model->elastic()->add($model);
        });

        static::updated(function ($model) {
            /* @var ElasticSearchable $model */
            $model->elastic()->update($model);
        });
    }

    /**
     * Build up index name. defined it uses the indexName, by default it uses class name to snake case.
     * If versioning is enabled it adds version tag to the index.
     *
     * @return string
     */
    public function getIndexName(): string
    {
        $index = $this->indexName ?? Str::snake(class_basename(static::class));

        if (isset($this->indexVersion)) {
            $index .= '_v'.$this->indexVersion;
        }

        return $index;
    }

    /**
     * Casting model to elasticsearch format.
     *
     * @return array
     */
    public function toSearchable(): array
    {
        return $this->toArray();
    }

    /**
     * @param array $item
     *
     * @return static
     */
    public function newFromElasticBuilder($item): self
    {
        $attributes = $item['_source'];

        if (isset($item['_id'])) {
            $attributes[$this->getKeyName()] = $this->getKeyType() === 'int' ? (int) $item['_id'] : $item['_id'];
        }

        if (isset($item['fields'])) {
            foreach ($item['fields'] as $key => $value) {
                $attributes[$key] = $value;
            }
        }

        /** @var static $instance */
        $instance = $this->newInstance([], true);
        $instance->setRawAttributes($attributes, true);

        $instance->elastic['score'] = $item['_score'] ?? 0.0;
        $instance->elastic['type'] = $item['_type'] ?? null;
        $instance->elastic['index'] = $item['_index'] ?? null;
        $instance->elastic['id'] = $item['_id'] ?? null;

        return $instance;
    }

    /**
     * @return \AviationCode\Elasticsearch\Elasticsearch|Elasticsearch
     */
    public function elastic()
    {
        return Elasticsearch::forModel($this);
    }

    /**
     * Get the mapping for the given model.
     *
     * @return array
     */
    public function getSearchMapping(): array
    {
        $properties = new Collection();

        switch ($this->getKeyType()) {
            case 'int':
                $properties->put($this->getKeyName(), ['type' => 'integer']);
                break;
            default:
                $properties->put($this->getKeyName(), ['type' => 'keyword']);
        }

        foreach ($this->getDates() as $date) {
            $properties->put($date, [
                'type'             => 'date',
                'ignore_malformed' => true,
                'format'           => 'yyyy-MM-dd HH:mm:ss||yyyy-MM-dd',
            ]);
        }

        foreach ($this->mapping ?? [] as $key => $mapping) {
            $properties->put($key, is_string($mapping) ? ['type' => $mapping] : $mapping);
        }

        return $properties->toArray();
    }

    /**
     * Get a meta field out of the model.
     *
     * @param string|int|null $attribute
     *
     * @return mixed
     */
    public function getElasticAttribute($attribute)
    {
        if ($attribute === null) {
            return $this->elastic;
        }

        return $this->elastic[$attribute] ?? null;
    }

    /**
     * Collection object used.
     *
     * @param array $models
     *
     * @return Collection
     */
    public function newCollection(array $models = []): Collection
    {
        return new ElasticCollection($models);
    }
}
