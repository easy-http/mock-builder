<?php

namespace EasyHttp\MockBuilder\Tests\Feature\HttpMock;

use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
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

        $mock = new HttpMock($builder);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $response = $client->post('https://example.com/v1/oauth2/token')->getBody()->getContents();

        $this->assertSame('bar', $response);
    }
}
