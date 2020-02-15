<?php

namespace AviationCode\Elasticsearch\Schema;

use AviationCode\Elasticsearch\Elasticsearch;
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
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    protected function getIndex(?string $index): string
    {
        if ($index) {
            return $index;
        }

        if ($this->elasticsearch->model !== null) {
            return $this->elasticsearch->model->getIndexName();
        }

        throw new InvalidArgumentException('Either index parameter or model has to be provided.');
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
        if (!$exception instanceof ElasticsearchException) {
            return $exception;
        }

        return ElasticErrorFactory::with($exception)->create();
    }
}
