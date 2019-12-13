<?php

namespace AviationCode\Elasticsearch\Exceptions;

use Elasticsearch\Common\Exceptions\ElasticsearchException;
use Exception;
use Illuminate\Support\Arr;

class ElasticErrorFactory
{
    /**
     * @var array Error type mapping
     */
    private static $types = [
        'resource_already_exists_exception' => BadRequestException::class,
        'index_not_found_exception' => IndexNotFoundException::class,
    ];

    /**
     * @var ElasticsearchException|Exception
     */
    private $exception;

    /**
     * ElasticErrorFactory constructor.
     *
     * @param ElasticsearchException|Exception $exception
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
    }


    /**
     * Bind the exception.
     *
     * @param ElasticsearchException $exception
     * @return $this
     */
    public static function with(ElasticsearchException $exception): self
    {
        return new static($exception);
    }

    /**
     * Map the exception and throw the correct error
     *
     * @throws BaseElasticsearchException
     */
    public function throw(): void
    {
        $type = Arr::get(json_decode($this->exception->getMessage(), true), 'error.type');

        if (!isset(static::$types[$type])) {
            throw new BaseElasticsearchException($this->exception);
        }

        throw new static::$types[$type]($this->exception);
    }
}
