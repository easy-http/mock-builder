<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Responses;

use App\ThreeDS\Constants\CreditCardCodes;
use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use EasyHttp\MockBuilder\Tests\Feature\Responses\Concerns\HasStatusCodesProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

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
    public function itSetsBodyResponseWithHandler()
    {
        $builder = new MockBuilder();
        $builder->when()->then()->body(
            function ($request) {
                /** @var RequestInterface $request */
                $stream = $request->getBody();
                $stream->rewind();
                $json = json_decode($stream->getContents(), true);

                $json['id'] = '34783e0d-1ec8-42ea-8d62-d34d4f44b452';

                return json_encode($json);
            }
        );
        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client
            ->withHandler($mock)
            ->prepareRequest('POST', '/users')
            ->setJson(['name' => 'Steve']);

        $response = $client->execute();

        $this->assertSame(['name' => 'Steve', 'id' => '34783e0d-1ec8-42ea-8d62-d34d4f44b452'], $response->parseJson());
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
