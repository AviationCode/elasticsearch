<?php

namespace AviationCode\Elasticsearch\Exceptions;

use Elasticsearch\Common\Exceptions\ElasticsearchException as BaseElasticException;
use Illuminate\Support\Arr;

class BaseElasticsearchException extends \Exception
{
    /**
     * ElasticsearchException constructor.
     * @param BaseElasticException $exception
     */
    public function __construct(BaseElasticException $exception)
    {
        parent::__construct(
            Arr::get(json_decode($exception->getMessage(), true), 'error.reason'),
            $exception->getCode(),
            $exception
        );
    }
}
