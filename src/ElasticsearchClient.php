<?php

namespace AviationCode\Elasticsearch;

use Elasticsearch\Client;

trait ElasticsearchClient
{
    /**
     * @var Client
     */
    protected $client;

    public function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = resolve('elasticsearch.client');
        }

        return  $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client): void
    {
        $this->client = $client;
    }
}
