<?php

namespace AviationCode\Elasticsearch\Model;

use AviationCode\Elasticsearch\Facades\Elasticsearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait ElasticSearchable
{
    public static function bootElasticSearchable()
    {
        static::created(function ($model) {
            /** @var ElasticSearchable $model */
            $model->elastic()->add($model);
        });

        static::updated(function ($model) {
            /** @var ElasticSearchable $model */
            $model->elastic()->update($model);
        });
    }

    public function getIndexName(): string
    {
        return $this->indexName ?? Str::snake(class_basename(static::class));
    }

    public function toSearchable()
    {
        return $this->toArray();
    }

    public function newFromElasticBuilder($item)
    {
        $attributes = $item['_source'];

        if (isset($item['_id'])) {
            $attributes[$this->getKeyName()] = $this->getKeyType() === 'int' ? (int)$item['_id'] : $item['_id'];
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

    public function elastic()
    {
        return Elasticsearch::forModel($this);
    }

    public function getSearchMapping()
    {
        $properties = new Collection();

        foreach ($this->getDates() as $date) {
            $properties->put($date, [
                'type' => 'date',
                'ignore_malformed' => true,
                'format' => 'yyyy-MM-dd HH:mm:ss||yyyy-MM-dd',
            ]);
        }

        return $properties->toArray();
    }
}
