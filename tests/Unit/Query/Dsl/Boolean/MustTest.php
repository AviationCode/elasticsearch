<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;

class MustTest extends BoolTest
{
    protected function newBooleanClass()
    {
        return new Must();
    }
}
