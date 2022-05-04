<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Expectations;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use PHPUnit\Framework\TestCase;

class PathIsExpectationTest extends TestCase
{
    /**
     * @test
     */
    public function itMatchesSamePath()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->pathIs('/v1/oauth2/token')
            ->then()
                ->body('bar');

        $client = new GuzzleClient();
        $mock = new HttpMock($builder);

        $response = $client
            ->withHandler($mock)
            ->call('POST', 'https://example.com/v1/oauth2/token');

        $this->assertSame('bar', $response->getBody());
    }

    /**
     * @test
     */
    public function itDoesNotMatchPath()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->pathIs('/v1/oauth2/token')
            ->then()
                ->body('bar');

        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $response = $client
            ->withHandler($mock)
            ->call('POST', 'https://example.com/v2/token');

        $this->assertSame(404, $response->getStatusCode());
    }
}
