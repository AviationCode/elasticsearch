<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Custom Elasticsearch Client Configuration
    |--------------------------------------------------------------------------
    |
    | This array will be passed to the Elasticsearch client.
    | See configuration options here:
    |
    | http://www.elasticsearch.org/guide/en/elasticsearch/client/php-api/current/_configuration.html
    */

    'config' => [
        'hosts' => [
            [
                'host'   => env('ELASTIC_HOST', 'localhost'),
                'port'   => env('ELASTIC_PORT', 9200),
                'scheme' => env('ELASTIC_SCHEME', 'http'),
                'user'   => env('ELASTIC_USER'),
                'pass'   => env('ELASTIC_PASSWORD'),
            ],
        ],
        'retries' => 1,
    ],
];
