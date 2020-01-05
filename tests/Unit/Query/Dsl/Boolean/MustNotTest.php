<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\MustNot;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Boolean;

class MustNotTest extends BoolTest
{
    protected function newBooleanClass(): Boolean
    {
        return new MustNot();
    }
}
