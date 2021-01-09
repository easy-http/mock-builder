<?php

namespace EasyHttp\MockBuilder\Tests;

use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;

class HttpMockTest extends TestCase
{
    /**
     * @test
     */
    public function itMatchesSameMethod()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->methodIs('POST')
            ->then()
                ->body('bar');

        $mock = new HttpMock($builder);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $response = $client->post('foo')->getBody()->getContents();

        $this->assertSame('bar', $response);
    }

    /**
     * @test
     * @dataProvider queryParamsProvider
     */
    public function itMatchesSameQueryParam(array $expectation, array $query, bool $matching)
    {
        $builder = new MockBuilder();
        $when = $builder->when();

        foreach ($expectation as $key => $value) {
            $when->queryParamIs($key, $value);
        }

        $when->then()->body('Hello World!');
        $mock = new HttpMock($builder);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $response = $client->get('foo', ['query' => $query]);

        if ($matching) {
            $this->assertSame('Hello World!', $response->getBody()->getContents());
        } else {
            $this->assertSame(404, $response->getStatusCode());
        }
    }

    public function queryParamsProvider(): array
    {
        return [
            'Same single expectation and query' => [['foo' => 'bar'], ['foo' => 'bar'], true],
            'Same multi expectation and query' => [['foo' => 'bar', 'x' => 'y'], ['foo' => 'bar', 'x' => 'y'], true],
            //'Different expectation and query' => [['foo' => 'bar'], ['bar' => 'baz'], false],
        ];
    }
}
