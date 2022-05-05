<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Expectations;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use PHPUnit\Framework\TestCase;

class MethodIsExpectationTest extends TestCase
{
    /**
     * @test
     */
    public function itMatchesMethod()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->methodIs('POST')
            ->then()
                ->body('foo');

        $client = new GuzzleClient();
        $mock = new HttpMock($builder);

        $response = $client
            ->withHandler($mock)
            ->call('POST', 'https://example.com/v1/oauth2/token');

        $this->assertSame('foo', $response->getBody());
    }

    /**
     * @test
     */
    public function itDoesNotMatchMethod()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->methodIs('POST')
            ->then()
                ->body('bar');

        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $response = $client
            ->withHandler($mock)
            ->call('GET', 'https://example.com/v2/token');

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('method \'GET\' does not match expectation', $response->getBody());
    }
}
