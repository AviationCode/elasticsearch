<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Term\Exists;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class ExistsTest extends TestCase
{
    #[Test]
    public function it_builds_exist_clause(): void
    {
        $exists = new Exists('user');

        $this->assertEquals(['exists' => ['field' => 'user']], $exists->toArray());
    }
}
