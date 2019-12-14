<?php

namespace AviationCode\Elasticsearch\Events;

use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Illuminate\Database\Eloquent\Model;

abstract class DocumentEvent
{
    /**
     * @var array
     */
    public $shards;
    /**
     * @var string
     */
    public $index;
    /**
     * @var Model|ElasticSearchable
     */
    public $model;
    /**
     * @var int
     */
    public $version;

    /**
     * DocumentCreatedEvent constructor.
     * @param ElasticSearchable|Model $model
     * @param array $response
     */
    public function __construct($model, $response)
    {
        $this->model = $model;
        $this->shards = $response['_shards'];
        $this->version = $response['_version'];
        $this->index = $response['_index'];
    }
}
