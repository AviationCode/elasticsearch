# Elasticsearch

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require aviationcode/elasticsearch
```
## Configuration

`.env` configuration

```dotenv
ELASTICSEARCH_HOST=localhost
ELASTICSEARCH_PORT=9200
```

## Usage

Configure a model to use elasticsearch by using the `ElasticSearchable` trait.

```php
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use ElasticSearchable;
    
    protected $mapping = [
        'body' => ['type' => 'text'],
    ];
}
```

This package can attempt migrate your elasticsearch index to it's desired state by running the artisan `elastic:migrate` command .
By default it will check the `App\` namespace. You can either provide your model as argument or set the `models_namespace` param in the configuration file.

```bash
php artisan elastic:migrate
php artisan elastic:migrate App/Article
```
In default setup the above three commands will execute in the same way if only model article exists and it uses the `ElasticSearchable` trait.

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

### Aggregations

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
