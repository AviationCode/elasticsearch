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
        'hosts' => [env('ELASTICSEARCH_HOST', 'localhost').':'.env('ELASTICSEARCH_PORT', 9200)],
        'retries' => 1,
    ],


    /*
    |--------------------------------------------------------------------------
    | Model classpath
    |--------------------------------------------------------------------------
    |
    | The namespace containing all models.
    |
    */
    'model_namespace' => 'App\\',
];
