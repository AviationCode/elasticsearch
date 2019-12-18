<?php

namespace AviationCode\Elasticsearch\Exceptions;

use Elasticsearch\Common\Exceptions\ElasticsearchException as BaseElasticException;
use Illuminate\Support\Arr;

class BaseElasticsearchException extends \Exception
{
    /**
     * ElasticsearchException constructor.
     * @param BaseElasticException|array $exception
     */
    public function __construct($exception)
    {
        if ($exception instanceof BaseElasticException) {
            parent::__construct(
                Arr::get(json_decode($exception->getMessage(), true), 'error.reason'),
                $exception->getCode(),
                $exception
            );

            return;
        }

        parent::__construct($exception['type'].': '.$exception['reason']);
    }
}
