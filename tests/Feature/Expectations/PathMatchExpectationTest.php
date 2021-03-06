<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Expectations;

use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;

class PathMatchExpectationTest extends TestCase
{
    /**
     * @test
     */
    public function itMatchesPathRegex()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
            ->pathMatch('/v1\/products\/[0-9]+/')
            ->then()
            ->body("{ name: 'first product' }");

        $mock = new HttpMock($builder);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $response = $client->post('https://example.com/v1/products/3534')->getBody()->getContents();

        $this->assertSame("{ name: 'first product' }", $response);
    }
}
