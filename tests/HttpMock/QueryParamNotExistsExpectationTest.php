<?php

namespace EasyHttp\MockBuilder\Tests\HttpMock;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use PHPUnit\Framework\TestCase;

class QueryParamNotExistsExpectationTest extends TestCase
{
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

        foreach ($expectation as $param) {
            $when->queryParamNotExists($param);
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
            'Parameter exists in query' => [['foo'], ['foo' => 'bar'], false],
            'Parameters exists in query' => [['a', 'x'], ['a' => 'b', 'x' => 'y'], false],
            'Parameter does not exists in query' => [['foo'], ['bar' => 'baz'], true],
            'Parameters does not exists in query' => [['a', 'x'], ['b' => 'a', 'y' => 'x'], true],
            'Only first parameter exists' => [['a', 'x'], ['a' => 'b'], false],
            'Only last parameter exists' => [['a', 'x'], ['x' => 'y'], false],
            'No expectation' => [[], ['a' => 'z', 'x' => 'y'], true],
        ];
    }
}
