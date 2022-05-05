<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Expectations;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use EasyHttp\MockBuilder\Tests\Feature\Expectations\Concerns\HasParametersProvider;
use PHPUnit\Framework\TestCase;

class HeaderNotExistsExpectationTest extends TestCase
{
    use HasParametersProvider;

    /**
     * @test
     * @dataProvider notExistingParamsProvider
     * @param array $expectation
     * @param array $headers
     * @param bool $matching
     */
    public function itMatchesQueryParams(array $expectation, array $headers, bool $matching)
    {
        $builder = new MockBuilder();
        $when = $builder->when();

        foreach ($expectation as $param) {
            $when->headerNotExist($param);
        }

        $when->then()->body('Hello World!');
        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client->withHandler($mock)->prepareRequest('POST', '/foo');

        foreach ($headers as $key => $value) {
            $client->getRequest()->setHeader($key, $value);
        }

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
    public function itMatchesWhenHeaderIsMissing()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->headerNotExist('Content-Type')
            ->then()
                ->body('bar');

        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $response = $client
            ->withHandler($mock)
            ->call('POST', 'https://example.com/v2/token');

        $this->assertSame('bar', $response->getBody());
    }

    /**
     * @test
     */
    public function itDoesNotMatchWhenHeaderIsPresent()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->headerNotExist('Content-Type')
            ->then()
                ->body('bar');

        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client
            ->withHandler($mock)
            ->prepareRequest('POST', 'https://example.com/v2/token')
            ->getRequest()
            ->setHeader('Content-Type', 'application/json');
        $response = $client->execute();

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('header \'Content-Type\' is present', $response->getBody());
    }
}
