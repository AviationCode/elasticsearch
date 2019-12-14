<?php

namespace AviationCode\Elasticsearch;

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
use InvalidArgumentException;

class Elasticsearch
{
    use ElasticsearchClient;

    /**
     * @var ElasticSearchable|Model
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
    private static $fireEvents = false;

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
     * @param null|ElasticSearchable|Model $model
     * @return bool
     *
     * @throws BaseElasticsearchException
     */
    public function add($model = null)
    {
        $model = $this->getModel($model);

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
            $this->handleException($exception);
        }

        return true;
    }

    /**
     * @param null|ElasticSearchable|Model $model
     * @return bool
     *
     * @throws BaseElasticsearchException
     */
    public function update($model = null)
    {
        return $this->add($model);
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
     * @return Builder
     * @throws \Throwable
     */
    public function query(): Builder
    {
        throw_unless($this->model, new \InvalidArgumentException('No model specified'));

        return new Builder($this->model);
    }

    /**
     * Fires events.
     *
     * @param $event
     */
    private function event($event)
    {
        if (static::shouldSentEvents()) {
            event($event);
        }
    }

    /**
     * If index is provided use it as param or use the model.
     *
     * @param null|ElasticSearchable|Model $model
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
     * @param Exception $exception
     *
     * @throws BaseElasticsearchException
     * @throws Exception
     */
    protected function handleException(Exception $exception): void
    {
        if (! $exception instanceof ElasticsearchException) {
            throw $exception;
        }

        ElasticErrorFactory::with($exception)->throw();
    }
}
