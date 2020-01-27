# Elasticsearch

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This package wraps the `elasticsearch/elasticsearch` composer package with laravel integration.
Adding support to easily use your eloquent models with elastic search.  

## Installation

Via Composer

``` bash
$ composer require aviationcode/elasticsearch
```
## Configuration

By default we use `localhost:9200` to search your elasticsearch instance. If this is the case noc configuration is required at all.
You can use the following `.env` settings to configure how we connect to your elasticsearch instance.

| Name             | Type      | Default     | Description
|------------------|:---------:|-------------|------------
| ELASTIC_HOST     | `string`  | `localhost` | The IP or host to connect to 
| ELASTIC_PORT     | `integer` | 9200        | The port used to connect
| ELASTIC_SCHEME   | `string`  | `http`      | Use of HTTP or HTTPS 
| ELASTIC_USER     | `string`  | `null`      | The Basic auth username
| ELASTIC_PASSWORD | `string`  | `null`      | The Basic auth password

## Usage

Configure a model to use elasticsearch by using the `ElasticSearchable` trait or extend using `ElasticsearchModel`. 

```php
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use ElasticSearchable;
}
```

### Custom mapping properties

We attempt to detect the [elasticsearch mapping](https://www.elastic.co/guide/en/elasticsearch/reference/current/mapping.html) fields from your `$dates` array and primary key. We are unable to correctly detect other fields. 

Elasticsearch will in this case attempt to guess the mapping field automatically however it is recommended to explicitly define these fields as the correct type.

```php
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use ElasticSearchable;
    
    public $mapping = [
        'category' => ['type' => 'keyword'],
        'properties' => ['type' => 'object', 'dynamic' => true],
        'ip' => ['type' => 'ip'],
    ];
}
```

Use the elasticsearch documentation to find all options available.

### Non numeric keys

When using UUID's or other non numeric keys make sure you configure your model correctly.
This will make sure we use the correct mapping inside your model mapping. 

```php
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use ElasticSearchable;

    protected $keyType = 'string';    
}
```

### Custom index name

You may want to use a custom name or use existing index name with your eloquent model. Just like you can define the database table used you can also define the index named used

```php
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use ElasticSearchable;
    
    public $indexName = 'my_custom_index_name';
}
```

**Note**: You can still use `$indexVersion` to add `vX` at the end of your index.

### Versioned index

If you like to version your index names you can use the `$indexVersion` name. This will add `_vX` at the end of your index name where X is the index version.
```php
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use ElasticSearchable;
    
    public $indexVersion = 2;
}
```

### Query

```php
namespace App\Http\Controllers;

use App\Article;
use AviationCode\Elasticsearch\Facades\Elasticsearch;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;
use Illuminate\Http\Request;

class ArticleController 
{
    public function index(Request $request)
    {
        return Elasticsearch::forModel(Article::class)
            ->query()
            ->filter(function (Filter $filter) use ($request) {
                if ($user = $request->query('user')) {
                    $filter->term('user', $user); 
                }
            })
            ->must(function (Must $must) use ($request) {
                if ($query = $request->query('q')) {
                    $must->queryString($query); 
                }        
            })
            ->get();
    }
}
```

Without an eloquent model.
```php
namespace App\Http\Controllers;

use App\Article;
use AviationCode\Elasticsearch\Facades\Elasticsearch;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;
use Illuminate\Http\Request;

class ArticleController 
{
    public function index(Request $request)
    {
        return Elasticsearch::query('article')
            ->filter(function (Filter $filter) use ($request) {
                if ($user = $request->query('user')) {
                    $filter->term('user', $user); 
                }
            })
            ->must(function (Must $must) use ($request) {
                if ($query = $request->query('q')) {
                    $must->queryString($query); 
                }        
            })
            ->get();
    }
}
```

### Aggregations

Using aggregations with model.
```php
namespace App\Http\Controllers;

use App\Article;
use AviationCode\Elasticsearch\Facades\Elasticsearch;

class ArticlesPerUserPerDayController 
{
    public function index()
    {
        $qb = Elasticsearch::forModel(Article::class)->query();

        $qb->aggregations()
            ->dateHistogram('date', 'created_at', '1d')
            ->terms('date.users', 'user');

        return $qb->get()->aggregations;
    }
}
```

Using aggregations without an eloquent model.
```php
namespace App\Http\Controllers;

use App\Article;
use AviationCode\Elasticsearch\Facades\Elasticsearch;

class ArticlesPerUserPerDayController 
{
    public function index()
    {
        $qb = Elasticsearch::query('article');

        $qb->aggregations()
            ->dateHistogram('date', 'created_at', '1d')
            ->terms('date.users', 'user');

        return $qb->get()->aggregations;
    }
}
```

## Console Commands 

### `elastic:create-index` Creating elasticsearch index

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover a security vulnerability within elasticsearch package, please send an e-mail to Ken Andries at ken.andries.1992@gmail.com. All security vulnerabilities will be promptly addressed.

## Credits

- [Ken Andries][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/aviationcode/elasticsearch.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/aviationcode/elasticsearch.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/aviationcode/elasticsearch/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/aviationcode/elasticsearch
[link-downloads]: https://packagist.org/packages/aviationcode/elasticsearch
[link-travis]: https://travis-ci.org/aviationcode/elasticsearch
[link-styleci]: https://styleci.io/repos/227918837
[link-author]: https://github.com/douglasdc3
[link-contributors]: ../../contributors
