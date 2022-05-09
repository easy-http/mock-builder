<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Expectations;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use PHPUnit\Framework\TestCase;

class BodyExpectationTest extends TestCase
{
    /**
     * @test
     */
    public function itMatchesBodyExpectation()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->body(
                    function ($body) {
                        $json = json_decode($body, true);

                        return isset($json['uuid']);
                    }
                )
            ->then()
                ->body('bar');

        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client
            ->withHandler($mock)
            ->prepareRequest('POST', 'https://example.com/v2/token')
            ->setJson(['uuid' => 'foo']);
        $response = $client->execute();

        $this->assertSame('bar', $response->getBody());
    }

    /**
     * @test
     */
    public function itDoesNotMatchBodyExpectation()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->body(
                    function ($body) {
                        $json = json_decode($body, true);

                        return isset($json['uuid']);
                    }
                )
            ->then()
                ->body('bar');

        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client
            ->withHandler($mock)
            ->prepareRequest('POST', 'https://example.com/v2/token')
            ->setJson(['x' => 'y']);
        $response = $client->execute();

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('body does not match expectation', $response->getBody());
    }
}
