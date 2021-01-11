<?php

namespace EasyHttp\MockBuilder\Tests\HttpMock;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use PHPUnit\Framework\TestCase;

class ExpectationTest extends TestCase
{
    /**
     * @test
     * @dataProvider requestProvider
     * @param array $expectation
     * @param array $request
     * @param $matching
     */
    public function itMatchesExpectedResponse(array $expectation, array $request, $matching)
    {
        $builder = new MockBuilder();
        $when = $builder->when();

        $when->methodIs($expectation['method']);
        foreach ($expectation['query'] as $key => $value) {
            $when->queryParamIs($key, $value);
        }

        $when->then()->body('Hello World!');
        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client->withHandler($mock)
            ->prepareRequest($request['method'], '/foo')
            ->getRequest()
            ->setQuery($request['query']);
        $response = $client->execute();

        if ($matching) {
            $this->assertSame('Hello World!', $response->getBody());
        } else {
            $this->assertSame(404, $response->getStatusCode());
        }
    }

    public function requestProvider(): array
    {
        return [
            'Same method and query' => [
                ['method' => 'POST', 'query' => ['foo' => 'bar']],
                ['method' => 'POST', 'query' => ['foo' => 'bar']],
                true
            ],
            'Different method but same query' => [
                ['method' => 'GET', 'query' => ['foo' => 'bar']],
                ['method' => 'POST', 'query' => ['foo' => 'bar']],
                false
            ],
            'Different query but same method' => [
                ['method' => 'POST', 'query' => ['foo' => 'bar']],
                ['method' => 'POST', 'query' => ['x' => 'y']],
                false
            ],
        ];
    }
}
