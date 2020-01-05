<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\MustNot;

class MustNotTest extends BoolTest
{
    protected function newBooleanClass()
    {
        return new MustNot();
    }
}
