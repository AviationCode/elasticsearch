<?php

namespace AviationCode\Elasticsearch\Events;

use AviationCode\Elasticsearch\Exceptions\ElasticErrorFactory;
use AviationCode\Elasticsearch\Model\ElasticSearchable;

class DocumentEvent
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
     * @var mixed|ElasticSearchable
     */
    public $model;
    /**
     * @var int
     */
    public $version;

    /**
     * @var string
     */
    public $action;

    /**
     * @var \AviationCode\Elasticsearch\Exceptions\BaseElasticsearchException
     */
    public $exception;

    /**
     * @var int|null
     */
    public $errorCode;

    /**
     * DocumentCreatedEvent constructor.
     *
     * @param mixed  $model
     * @param string $action
     * @param array  $response
     */
    public function __construct($model, string $action, array $response)
    {
        $this->model = $model;
        $this->action = $action;
        $this->index = $response['_index'];

        if (isset($response['error'])) {
            $this->errorCode = $response['status'];
            $this->exception = ElasticErrorFactory::from($response['error']);

            return;
        }

        $this->shards = $response['_shards'];
        $this->version = $response['_version'];
    }
}
