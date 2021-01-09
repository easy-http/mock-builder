<?php

namespace EasyHttp\MockBuilder\Tests;

use EasyHttp\GuzzleLayer\GuzzleClient;
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
     * @param array $expectation
     * @param array $query
     * @param bool $matching
     */
    public function itMatchesQueryParams(array $expectation, array $query, bool $matching)
    {
        $builder = new MockBuilder();
        $when = $builder->when();

        foreach ($expectation as $key => $value) {
            $when->queryParamIs($key, $value);
        }

        $when->then()->body('Hello World!');
        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client->withHandler($mock)
            ->prepareRequest('POST', '/foo')
            ->getRequest()
            ->setQuery($query);
        $response = $client->execute();

        if ($matching) {
            $this->assertSame('Hello World!', $response->getBody());
        } else {
            $this->assertSame(404, $response->getStatusCode());
        }
    }

    public function queryParamsProvider(): array
    {
        return [
            'Same parameter expectation and query' => [['foo' => 'bar'], ['foo' => 'bar'], true],
            'Same parameters expectation and query' => [['a' => 'b', 'x' => 'y'], ['a' => 'b', 'x' => 'y'], true],
            'Same parameter expectation and different query value' => [['foo' => 'bar'], ['foo' => 'baz'], false],
            'Different parameter expectation and values' => [['foo' => 'bar'], ['bar' => 'baz'], false],
            'Match only first parameter' => [['a' => 'b', 'x' => 'y'], ['a' => 'b', 'x' => 'z'], false],
            'Match only last parameter' => [['a' => 'b', 'x' => 'y'], ['a' => 'z', 'x' => 'y'], false],
            'No expectation' => [[], ['a' => 'z', 'x' => 'y'], false],
        ];
    }
}
