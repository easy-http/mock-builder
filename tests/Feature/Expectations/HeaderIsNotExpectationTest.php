<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Expectations;

use EasyHttp\GuzzleLayer\GuzzleClient;
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use PHPUnit\Framework\TestCase;

class HeaderIsNotExpectationTest extends TestCase
{
    /**
     * @test
     * @dataProvider paramsProvider
     * @param array $expectation
     * @param array $headers
     * @param bool $matching
     */
    public function itMatchesExpectations(array $expectation, array $headers, bool $matching)
    {
        $builder = new MockBuilder();
        $when = $builder->when();

        foreach ($expectation as $key => $value) {
            $when->headerIsNot($key, $value);
        }

        $when->then()->body('Hello World!');
        $client = new GuzzleClient();
        $client->withHandler(new HttpMock($builder))->prepareRequest('POST', '/foo');

        foreach ($headers as $key => $value) {
            $client->getRequest()->setHeader($key, $value);
        }

        $response = $client->execute();

        if ($matching) {
            // match, return expectation
            $this->assertSame('Hello World!', $response->getBody());
        } else {
            // no match, go to fallback response
            $this->assertSame(404, $response->getStatusCode());
        }
    }

    /**
     * @test
     */
    public function itMatchesWithOthersConditions()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->methodIs('POST')
                ->pathIs('/auth')
                ->headerIsNot('Authorization', base64_encode('user:pass'))
            ->then()
                ->statusCode(401)
                ->body('Unauthorized');

        $mock = new HttpMock($builder);

        $client = new GuzzleClient();
        $client->withHandler($mock);
        $responseWithoutAuth = $client->prepareRequest('POST', '/auth')->execute();

        $client->getRequest()->setHeader('Authorization', base64_encode('user:pass'));
        $responseWithAuth = $client->execute();

        // match, return expectation
        $this->assertSame(401, $responseWithoutAuth->getStatusCode());
        $this->assertSame('Unauthorized', $responseWithoutAuth->getBody());

        // no match, go to fallback response
        $this->assertSame(404, $responseWithAuth->getStatusCode());
    }

    /**
     * @test
     */
    public function itMatchesWithOtherExpectations()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->methodIs('POST')
                ->pathIs('/protected')
                ->headerIsNot('Authorization', base64_encode('user:pass'))
            ->then()
                ->statusCode(401)
                ->body('Unauthorized');

        $builder
            ->when()
                ->methodIs('POST')
                ->pathIs('/protected')
            ->then()
                ->statusCode(200)
                ->body('Protected resource');

        $client = new GuzzleClient();
        $client
            ->withHandler(new HttpMock($builder))
            ->prepareRequest('POST', '/protected');
        $client
            ->getRequest()
            ->setHeader('Authorization', base64_encode('user:pass'));
        $response = $client->execute();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Protected resource', $response->getBody());
    }

    /**
     * This parameters can be use as query parameters or headers
     *
     * @return array[]
     */
    public function paramsProvider(): array
    {
        return [
            'Same parameter expectation and parameter' => [['foo' => 'bar'], ['foo' => 'bar'], false],
            'Same parameters expectation and parameter' => [['a' => 'b', 'x' => 'y'], ['a' => 'b', 'x' => 'y'], false],
            'Same parameter expectation and different parameter value' => [['foo' => 'bar'], ['foo' => 'baz'], true],
            'Different parameter expectation and values' => [['foo' => 'bar'], ['bar' => 'baz'], true],
            'Match only first parameter' => [['a' => 'b', 'x' => 'y'], ['a' => 'b', 'x' => 'z'], false],
            'Match only last parameter' => [['a' => 'b', 'x' => 'y'], ['a' => 'z', 'x' => 'y'], false],
            'No expectation' => [[], ['a' => 'z', 'x' => 'y'], true],
        ];
    }
}
