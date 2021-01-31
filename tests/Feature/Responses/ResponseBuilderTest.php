<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Responses;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use EasyHttp\MockBuilder\Tests\Feature\Responses\Concerns\HasStatusCodesProvider;
use PHPUnit\Framework\TestCase;

class ResponseBuilderTest extends TestCase
{
    use HasStatusCodesProvider;

    /**
     * @test
     * @dataProvider statusCodesProvider
     * @param int $statusCode
     */
    public function itSetsStatusCodeResponse(int $statusCode)
    {
        $builder = new MockBuilder();
        $builder->when()->then()->statusCode($statusCode);
        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client->withHandler($mock)->prepareRequest('POST', '/foo');

        $response = $client->execute();

        $this->assertSame($statusCode, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function itSetsHeaders()
    {
        $builder = new MockBuilder();
        $builder->when()->then()->headers(
            [
            'foo' => 'bar',
            'bar' => 'baz'
            ]
        );
        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client->withHandler($mock)->prepareRequest('POST', '/foo');

        $response = $client->execute();

        $this->assertSame(
            [
            'foo' => 'bar',
            'bar' => 'baz'
            ],
            $response->getHeaders()
        );
    }

    /**
     * @test
     */
    public function itSetsBodyResponse()
    {
        $builder = new MockBuilder();
        $builder->when()->then()->body('Hello World');
        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client->withHandler($mock)->prepareRequest('POST', '/foo');

        $response = $client->execute();

        $this->assertSame('Hello World', $response->getBody());
    }

    /**
     * @test
     */
    public function itSetsJsonResponse()
    {
        $builder = new MockBuilder();
        $builder->when()->then()->json(['foo' => 'bar']);
        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client->withHandler($mock)->prepareRequest('POST', '/foo');

        $response = $client->execute();

        $this->assertSame(['foo' => 'bar'], $response->parseJson());
    }
}
