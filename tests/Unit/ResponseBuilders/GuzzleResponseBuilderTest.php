<?php

namespace EasyHttp\MockBuilder\Tests\Unit\ResponseBuilders;

use EasyHttp\MockBuilder\ResponseBuilders\GuzzleResponseBuilder;
use PHPUnit\Framework\TestCase;

class GuzzleResponseBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function itSetsBodyInsteadOfJson()
    {
        $builder = new GuzzleResponseBuilder();
        $builder->json(['foo' => 'bar']);

        $builder->body('hello world');

        $this->assertEmpty($builder->getJson());
        $this->assertSame('hello world', $builder->getBody());
    }

    /**
     * @test
     */
    public function itSetsJsonInsteadOfPlainBody()
    {
        $builder = new GuzzleResponseBuilder();
        $builder->body('hello world');

        $builder->json(['foo' => 'bar']);

        $this->assertEmpty($builder->getBody());
        $this->assertSame(['foo' => 'bar'], $builder->getJson());
    }
}
