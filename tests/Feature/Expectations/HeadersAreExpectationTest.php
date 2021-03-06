<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Expectations;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use EasyHttp\MockBuilder\Tests\Feature\Expectations\Concerns\HasParametersProvider;
use PHPUnit\Framework\TestCase;

class HeadersAreExpectationTest extends TestCase
{
    use HasParametersProvider;

    /**
     * @test
     * @dataProvider paramsProvider
     * @param array $expectation
     * @param array $headers
     * @param bool $matching
     */
    public function itMatchesQueryParams(array $expectation, array $headers, bool $matching)
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->headersAre($expectation)
            ->then()
                ->body('Hello World!');

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
}
