<?php

namespace EasyHttp\MockBuilder\Tests\HttpMock;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use EasyHttp\MockBuilder\Tests\HttpMock\Concerns\HasParametersProvider;
use PHPUnit\Framework\TestCase;

class QueryParamsNotExistsExpectationTest extends TestCase
{
    use HasParametersProvider;

    /**
     * @test
     * @dataProvider notExistingParamsProvider
     * @param array $expectation
     * @param array $query
     * @param bool $matching
     */
    public function itMatchesQueryParams(array $expectation, array $query, bool $matching)
    {
        $builder = new MockBuilder();
        $when = $builder->when();

        $when->queryParamsNotExists($expectation);

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
}