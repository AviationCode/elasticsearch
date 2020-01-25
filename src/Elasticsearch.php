<?php

namespace AviationCode\Elasticsearch;

use AviationCode\Elasticsearch\Events\BulkDocumentsEvent;
use AviationCode\Elasticsearch\Events\DocumentCreatedEvent;
use AviationCode\Elasticsearch\Events\DocumentUpdatedEvent;
use AviationCode\Elasticsearch\Exceptions\BaseElasticsearchException;
use AviationCode\Elasticsearch\Exceptions\ElasticErrorFactory;
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use AviationCode\Elasticsearch\Query\Builder;
use AviationCode\Elasticsearch\Schema\Index;
use Elasticsearch\Common\Exceptions\ElasticsearchException;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class Elasticsearch
{
    use ElasticsearchClient;

    /**
     * @var ElasticSearchable|null
     */
    public $model;

    /**
     * @var Index
     */
    private $index;

    /**
     * Should events be triggered when actions are taken on Elasticsearch.
     *
     * @var bool
     */
    protected static $fireEvents = false;

    /**
     * Elasticsearch constructor.
     *
     * @param null|string|Model $model
     */
    public function __construct($model = null)
    {
        $this->forModel($model);

        $this->index = new Index($this);
    }

    /**
     * Set the model.
     *
     * @param null|string|Model $model
     *
     * @return $this
     */
    public function forModel($model)
    {
        if (is_string($model)) {
            $model = new $model;
        }

        $this->model = $model;

        return $this;
    }

    /**
     * When enabled fires events under the elasticsearch.* namesapce.
     *
     * @param bool $enable
     *
     * @return void
     */
    public static function enableEvents(bool $enable = true): void
    {
        static::$fireEvents = $enable;
    }

    /**
     * Should events be fired?
     *
     * @return bool
     */
    public static function shouldSentEvents(): bool
    {
        return static::$fireEvents;
    }

    /**
     * Index a model into elastic search.
     *
     * @param null|ElasticSearchable $model
     * @param null $data
     * @param string $key
     * @return bool
     *
     * @throws BaseElasticsearchException
     * @throws \Throwable
     */
    public function add($model = null, $data = null, $key = 'id')
    {
        if ($data !== null) {
            return $this->addRaw($model, $data, $key);
        }

        $model = $this->getModel($model);

        if ($model instanceof Collection) {
            return $this->bulk($model);
        }

        try {
            $response = $this->getClient()->index([
                'id' => $model->getKey(),
                'index' => $model->getIndexName(),
                'body' => $model->toSearchable(),
            ]);

            if ($response['result'] === 'created') {
                $this->event(new DocumentCreatedEvent($model, $response));
            }

            if ($response['result'] === 'updated') {
                $this->event(new DocumentUpdatedEvent($model, $response));
            }
        } catch (\Exception $exception) {
            throw $this->handleException($exception);
        }

        return true;
    }

    /**
     * Index native php objects / arrays.
     *
     * @param string $index
     * @param array|\stdClass|\stdClass[] $data
     * @param string $key
     *
     * @return bool
     *
     * @throws BaseElasticsearchException
     * @throws \Throwable
     */
    private function addRaw(string $index, $data, $key = 'id')
    {
        $data = (array) $data;

        if (! Arr::isAssoc($data)) {
            return $this->bulkRaw($index, $data, $key);
        }

        try {
            $response = $this->getClient()->index([
                'id' => $data[$key],
                'index' => $index,
                'body' => $data,
            ]);

            if ($response['result'] === 'created') {
                $this->event(new DocumentCreatedEvent($data, $response));
            }

            if ($response['result'] === 'updated') {
                $this->event(new DocumentUpdatedEvent($data, $response));
            }
        } catch (Exception $exception) {
            throw $this->handleException($exception);
        }

        return true;
    }

    /**
     * @param null|ElasticSearchable $model
     * @param null $data
     * @param string $key
     * @return bool
     *
     * @throws BaseElasticsearchException
     * @throws \Throwable
     */
    public function update($model = null, $data = null, $key = 'id')
    {
        return $this->add($model, $data, $key);
    }

    /**
     * Bulk index models.
     *
     * @param Collection|ElasticSearchable[] $models
     * @param null $data
     * @param string $key
     * @return bool
     */
    public function bulk($models, $data = null, $key = 'id')
    {
        if ($data !== null) {
            return $this->bulkRaw($models, $data, $key);
        }

        $response = $this->getClient()->bulk([
            'refresh' => true,
            'body' => $models->map(function ($model) {
                /* @var ElasticSearchable $model */
                return $this->toNdJson($model, [
                    'index' => [
                        '_index' => $model->getIndexName(),
                        '_id' => $model->getKey(),
                    ],
                ]);
            })->implode(''),
        ]);

        $this->event(function () use ($response, $models) {
            return new BulkDocumentsEvent($models, $response);
        });

        return true;
    }

    /**
     * Bulk index raw php objects.
     *
     * @param string $index
     * @param array|\stdClass[] $data
     * @param string $key
     * @return bool
     */
    private function bulkRaw(string $index, $data, $key = 'id')
    {
        $response = $this->getClient()->bulk([
            'refresh' => true,
            'body' => implode(array_map(function ($item) use ($index, $key) {
                $item = (array) $item;

                return implode(PHP_EOL, [
                    json_encode(['index' => ['_index' => $index, '_id' => $item[$key]]]),
                    json_encode($item),
                ]).PHP_EOL;
            }, $data)),
        ]);

        $this->event(function () use ($response, $data) {
            return new BulkDocumentsEvent($data, $response);
        });

        return true;
    }

    /**
     * Access index management.
     *
     * @return Index
     */
    public function index(): Index
    {
        return $this->index;
    }

    /**
     * Create a query builder.
     *
     * @param $model
     * @return Builder
     * @throws \Throwable
     */
    public function query($model = null): Builder
    {
        throw_unless($this->model || $model, new \InvalidArgumentException('No model specified'));

        return new Builder($this->model ?? $model);
    }

    /**
     * Fires events.
     *
     * @param mixed $event
     */
    private function event($event)
    {
        if (static::shouldSentEvents()) {
            if (is_callable($event)) {
                $event = $event();
            }

            event($event);
        }
    }

    /**
     * If index is provided use it as param or use the model.
     *
     * @param null|ElasticSearchable $model
     * @return ElasticSearchable|Model
     * @throws InvalidArgumentException
     */
    protected function getModel($model = null)
    {
        if (! ($model || $this->model)) {
            throw new InvalidArgumentException('No model provided.');
        }

        return $model ?? $this->model;
    }

    /**
     * Handle erorr / exceptions.
     *
     * @param \Throwable $exception
     *
     * @return \Throwable
     */
    protected function handleException(\Throwable $exception): \Throwable
    {
        if (! $exception instanceof ElasticsearchException) {
            return $exception;
        }

        return ElasticErrorFactory::with($exception)->create();
    }

    /**
     * Convert the model to a index ndjson record.
     *
     * @param ElasticSearchable $model
     * @param array $meta
     * @return string
     */
    private function toNdJson($model, $meta = []): string
    {
        return implode(PHP_EOL, [
            json_encode($meta),
            json_encode($model->toSearchable()),
        ]).PHP_EOL;
    }
}
