<?php

namespace AviationCode\Elasticsearch\Schema;

use AviationCode\Elasticsearch\Elasticsearch;
use AviationCode\Elasticsearch\Exceptions\BaseElasticsearchException;
use AviationCode\Elasticsearch\Exceptions\ElasticErrorFactory;
use Elasticsearch\Common\Exceptions\ElasticsearchException;
use InvalidArgumentException;

class Schema
{
    /**
     * @var Elasticsearch
     */
    protected $elasticsearch;

    /**
     * Schema constructor.
     *
     * @param Elasticsearch $elasticsearch
     */
    public function __construct(Elasticsearch $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * If index is provided use it as param or use the model.
     *
     * @param string $index
     * @return string
     * @throws InvalidArgumentException
     */
    protected function getIndex(?string $index): string
    {
        if (! ($index || $this->elasticsearch->model)) {
            throw new InvalidArgumentException('Either index parameter or model has to be provided.');
        }

        return $index ?? $this->elasticsearch->model->getIndexName();
    }

    /**
     * Handle erorr / exceptions.
     *
     * @param \Exception $exception
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
}
