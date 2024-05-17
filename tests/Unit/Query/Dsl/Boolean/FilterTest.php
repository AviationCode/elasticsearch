<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Boolean;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter;

class FilterTest extends BoolTestCase
{
    protected function newBooleanClass(): Boolean
    {
        return new Filter();
    }
}
