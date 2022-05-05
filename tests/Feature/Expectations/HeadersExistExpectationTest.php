<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Expectations;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use EasyHttp\MockBuilder\Tests\Feature\Expectations\Concerns\HasParametersProvider;
use PHPUnit\Framework\TestCase;

class HeadersExistExpectationTest extends TestCase
{
    use HasParametersProvider;

    /**
     * @test
     * @dataProvider existingParamsProvider
     * @param array $expectation
     * @param array $headers
     * @param bool $matching
     */
    public function itMatchesWhenAHeaderExists(array $expectation, array $headers, bool $matching)
    {
        $builder = new MockBuilder();
        $when = $builder->when();

        $when->headersExist($expectation);

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
    public function itDoesNotMatchWhenAHeaderDoesNotExists()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->headerExists('Content-Type')
            ->then()
                ->body('bar');

        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $response = $client
            ->withHandler($mock)
            ->call('POST', 'https://example.com/v2/token');

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('header \'Content-Type\' is missing', $response->getBody());
    }
}
