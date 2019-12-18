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
 */
trait ElasticSearchable
{
    public static function bootElasticSearchable()
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

    public function getIndexName(): string
    {
        $index = $this->indexName ?? Str::snake(class_basename(static::class));

        if (isset($this->indexVersion)) {
            $index .= '_v'.$this->indexVersion;
        }

        return $index;
    }

    public function toSearchable()
    {
        return $this->toArray();
    }

    public function newFromElasticBuilder($item)
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

        /** @var Model $instance */
        $instance = $this->newInstance([], true);
        $instance->setRawAttributes($attributes, true);

        return $instance;
    }

    /**
     * @return Elasticsearch
     */
    public function elastic()
    {
        return Elasticsearch::forModel($this);
    }

    public function getSearchMapping()
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
                'type' => 'date',
                'ignore_malformed' => true,
                'format' => 'yyyy-MM-dd HH:mm:ss||yyyy-MM-dd',
            ]);
        }

        foreach ($this->mapping ?? [] as $key => $mapping) {
            $properties->put($key, is_string($mapping) ? ['type' => $mapping] : $mapping);
        }

        return $properties->toArray();
    }
}
