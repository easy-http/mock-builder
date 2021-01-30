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
    public function itReturnsAssignedStatusCode(int $statusCode)
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
    public function itReturnsAssignedBody()
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
    public function itReturnsAssignedJson()
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
