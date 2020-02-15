<?php

namespace AviationCode\Elasticsearch\Schema;

use AviationCode\Elasticsearch\Exceptions\BaseElasticsearchException;
use AviationCode\Elasticsearch\Model\Index as IndexModel;
use Exception;
use Illuminate\Support\Collection;

class Index extends Schema
{
    /**
     * List all indices.
     *
     * @return Collection
     */
    public function list()
    {
        $response = $this->elasticsearch->getClient()->cat()->indices();

        return (new Collection($response))->mapInto(IndexModel::class);
    }

    /**
     * Check if the given index exists.
     *
     * @param string|null $index
     *
     * @return bool
     */
    public function exists($index = null): bool
    {
        return $this->elasticsearch->getClient()->indices()->exists([
            'index' => $this->getIndex($index),
        ]);
    }

    /**
     * @param null|string $index
     *
     * @throws \Throwable
     *
     * @return array
     */
    public function info($index = null)
    {
        try {
            $response = $this->elasticsearch->getClient()->indices()->get(['index' => $this->getIndex($index)]);

            return $response[$this->getIndex($index)];
        } catch (Exception $exception) {
            throw $this->handleException($exception);
        }
    }

    /**
     * Create an index.
     *
     * @param null|string $index
     *
     * @throws BaseElasticsearchException
     * @throws \Throwable
     *
     * @return bool
     */
    public function create($index = null)
    {
        if ($this->elasticsearch->model && $index !== null) {
            throw new \InvalidArgumentException('Expected either a model or index received both.');
        }

        try {
            $this->elasticsearch->getClient()->indices()->create(['index' => $this->getIndex($index)]);
        } catch (Exception $exception) {
            throw $this->handleException($exception);
        }

        if ($this->elasticsearch->model) {
            $this->putMapping();
        }

        return true;
    }

    /**
     * Delete an index and it's data.
     *
     * @param null $index
     *
     * @throws BaseElasticsearchException
     * @throws \Throwable
     *
     * @return void
     */
    public function delete($index = null): void
    {
        try {
            $this->elasticsearch->getClient()->indices()->delete(['index' => $this->getIndex($index)]);
        } catch (Exception $exception) {
            throw $this->handleException($exception);
        }
    }

    /**
     * Create or update index mapping.
     *
     * @param array $mappings
     * @param null  $index
     *
     * @throws BaseElasticsearchException
     * @throws \Throwable
     *
     * @return void
     */
    public function putMapping(?array $mappings = null, $index = null): void
    {
        if (!$mappings && $this->elasticsearch->model !== null) {
            $mappings = $this->elasticsearch->model->getSearchMapping();
        }

        try {
            $this->elasticsearch->getClient()->indices()->putMapping([
                'index' => $this->getIndex($index),
                'body'  => ['properties' => $mappings],
            ]);
        } catch (Exception $exception) {
            throw $this->handleException($exception);
        }
    }
}
