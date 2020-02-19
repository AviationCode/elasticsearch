<?php

namespace AviationCode\Elasticsearch;

use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;
use Illuminate\Contracts\Foundation\Application;

class FakeElasticsearch extends Elasticsearch
{
    /**
     * @var IndicesNamespace|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    public $indicesClient;

    /**
     * FakeElasticsearch constructor.
     */
    public function __construct(Application $app)
    {
        parent::__construct(null);

        $this->setClient($this->client = \Mockery::mock(Client::class));
        if ($app) {
            $app->instance('elasticsearch.client', $this->client);
        }

        $this->client
            ->shouldReceive('indices')
            ->andReturn($this->indicesClient = \Mockery::mock(IndicesNamespace::class));
    }
}
