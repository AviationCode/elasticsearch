<?php

namespace AviationCode\Elasticsearch\Schema;

use AviationCode\Elasticsearch\Exceptions\BaseElasticsearchException;
use Exception;

class Index extends Schema
{
    /**
     * Check if the given index exists.
     *
     * @param string|null $index
     * @return bool
     */
    public function exists($index = null): bool
    {
        return $this->elasticsearch->getClient()->indices()->exists([
            'index' => $this->getIndex($index),
        ]);
    }

    /**
     * Create an index.
     *
     * @param null $index
     * @return bool
     *
     * @throws BaseElasticsearchException
     */
    public function create($index = null)
    {
        try {
            $this->elasticsearch->getClient()->indices()->create(['index' => $this->getIndex($index)]);
        } catch (Exception $exception) {
            $this->handleException($exception);
        }

        if ($this->elasticsearch->model) {
            $this->putMapping($this->elasticsearch->model->getSearchMapping(), $index);
        }

        return true;
    }

    /**
     * Delete an index and it's data.
     *
     * @param null $index
     * @return void
     *
     * @throws BaseElasticsearchException
     */
    public function delete($index = null): void
    {
        try {
            $this->elasticsearch->getClient()->indices()->delete(['index' => $this->getIndex($index)]);
        } catch (Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * Create or update index mapping.
     *
     * @param array $mappings
     * @param null $index
     * @return void
     */
    public function putMapping(array $mappings, $index = null): void
    {
        $this->elasticsearch->getClient()->indices()->putMapping([
            'index' => $this->getIndex($index),
            'body' => [
                'properties' => $mappings,
            ],
        ]);
    }
}
