<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Expectations;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use EasyHttp\MockBuilder\Tests\Feature\Expectations\Concerns\HasParametersProvider;
use PHPUnit\Framework\TestCase;

class QueryParamIsExpectationTest extends TestCase
{
    use HasParametersProvider;

    /**
     * @test
     * @dataProvider paramsProvider
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

    /**
     * @test
     */
    public function itDoesNotMatchWhenAQueryParamIsMissing()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->queryParamIs('foo', 'baz')
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

    /**
     * @test
     */
    public function itDoesNotMatchWhenAQueryParamIsDifferentFromExpectation()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->queryParamIs('foo', 'baz')
            ->then()
                ->body('bar');

        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client
            ->withHandler($mock)
            ->prepareRequest('POST', 'https://example.com/v2/token')
            ->getRequest()
            ->setQuery(['foo' => 'application']);
        $response = $client->execute();

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('param \'foo\' does not match expectation', $response->getBody());
    }
}
