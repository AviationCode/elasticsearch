<?php

namespace AviationCode\Elasticsearch;

use Elasticsearch\Client;

trait ElasticsearchClient
{
    protected $client;

    public function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = resolve('elasticsearch.client');
        }

        return  $this->client;
    }

    public function setElasticClient(Client $client): void
    {
        $this->client = $client;
    }
}
