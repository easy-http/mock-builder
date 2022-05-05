<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Expectations;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use EasyHttp\MockBuilder\Tests\Feature\Expectations\Concerns\HasParametersProvider;
use PHPUnit\Framework\TestCase;

class QueryParamExistsExpectationTest extends TestCase
{
    use HasParametersProvider;

    /**
     * @test
     * @dataProvider existingParamsProvider
     * @param array $expectation
     * @param array $query
     * @param bool $matching
     */
    public function itMatchesWhenAQueryParamExists(array $expectation, array $query, bool $matching)
    {
        $builder = new MockBuilder();
        $when = $builder->when();

        foreach ($expectation as $param) {
            $when->queryParamExists($param);
        }

        $when->then()->body('Hello World!');
        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client->withHandler($mock)
            ->prepareRequest('POST', '/foo')
            ->setQuery($query);
        $response = $client->execute();

        if ($matching) {
            $this->assertSame('Hello World!', $response->getBody());
        } else {
            $this->assertSame(404, $response->getStatusCode());
        }
    }

    /**
     * @test
     */
    public function itDoesNotMatchWhenAQueryParamDoesNotExists()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->queryParamExists('foo')
            ->then()
                ->body('bar');

        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $response = $client
            ->withHandler($mock)
            ->call('POST', 'https://example.com/v2/token');

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('param \'foo\' is missing', $response->getBody());
    }
}
