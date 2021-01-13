<?php

namespace EasyHttp\MockBuilder\Tests\HttpMock\Concerns;

trait HasQueryParametersProvider
{
    public function queryParamsProvider(): array
    {
        return [
            'Same parameter expectation and query' => [['foo' => 'bar'], ['foo' => 'bar'], true],
            'Same parameters expectation and query' => [['a' => 'b', 'x' => 'y'], ['a' => 'b', 'x' => 'y'], true],
            'Same parameter expectation and different query value' => [['foo' => 'bar'], ['foo' => 'baz'], false],
            'Different parameter expectation and values' => [['foo' => 'bar'], ['bar' => 'baz'], false],
            'Match only first parameter' => [['a' => 'b', 'x' => 'y'], ['a' => 'b', 'x' => 'z'], false],
            'Match only last parameter' => [['a' => 'b', 'x' => 'y'], ['a' => 'z', 'x' => 'y'], false],
            'No expectation' => [[], ['a' => 'z', 'x' => 'y'], true],
        ];
    }
}
