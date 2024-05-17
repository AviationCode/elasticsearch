<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Boolean;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Should;

class ShouldTest extends BoolTestCase
{
    protected function newBooleanClass(): Boolean
    {
        return new Should();
    }
}
