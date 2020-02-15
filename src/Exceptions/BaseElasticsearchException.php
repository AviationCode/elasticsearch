<?php

namespace AviationCode\Elasticsearch\Exceptions;

use Elasticsearch\Common\Exceptions\ElasticsearchException as BaseElasticException;
use Illuminate\Support\Arr;

class BaseElasticsearchException extends \RuntimeException
{
    /**
     * ElasticsearchException constructor.
     *
     * @param BaseElasticException|\Exception|array $exception
     */
    public function __construct($exception)
    {
        $message = null;
        $code = null;
        $previous = null;

        if ($exception instanceof BaseElasticException) {
            $message = Arr::get(json_decode($exception->getMessage(), true), 'error.reason') ?? $exception->getMessage();
            $code = $exception->getCode();
            $previous = $exception;
        } elseif (is_array($exception)) {
            $message = "{$exception['type']}: {$exception['reason']}";
        }

        parent::__construct($message, $code, $previous);
    }
}
