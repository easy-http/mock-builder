<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Expectations;

use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;

class MethodExpectationTest extends TestCase
{
    /**
     * @test
     */
    public function itMatchesSameMethod()
    {
        $builder = new MockBuilder();
        $builder
            ->when()
                ->methodIs('POST')
            ->then()
                ->body('bar');

        $mock = new HttpMock($builder);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $response = $client->post('foo')->getBody()->getContents();

        $this->assertSame('bar', $response);
    }
}
