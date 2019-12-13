<?php

namespace AviationCode\Elasticsearch;

use AviationCode\Elasticsearch\Model\ElasticSearchable;
use AviationCode\Elasticsearch\Query\Builder;
use AviationCode\Elasticsearch\Schema\Index;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

    public function add($model = null)
    {
        $model = $model ?? $this->model;

        $params = [
            'id' => $model->getKey(),
            'index' => $model->getIndexName(),
            'body' => $model->toSearchable(),
        ];

        try {
            $response = $this->getClient()->index($params);
        } catch (\Exception $exception) {
        }

        Log::debug(vsprintf(
            'Elasticsearch: %s record in "%s" with %s: %s', [
                $response['result'],
                $model->getIndexName(),
                $model->getKeyName(),
                $model->getKey(),
            ]
        ));

        return true;
    }

    public function update($model = null)
    {
        return $this->add($model);
    }

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
}
