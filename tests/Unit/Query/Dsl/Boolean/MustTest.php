<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Boolean;

class MustTest extends BoolTest
{
    protected function newBooleanClass(): Boolean
    {
        return new Must();
    }
}
