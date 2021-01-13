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

    public function existingQueryParamsProvider(): array
    {
        return [
            'Parameter exists in query' => [['foo'], ['foo' => 'bar'], true],
            'Parameters exists in query' => [['a', 'x'], ['a' => 'b', 'x' => 'y'], true],
            'Parameter does not exists in query' => [['foo'], ['bar' => 'baz'], false],
            'Parameters does not exists in query' => [['a', 'x'], ['b' => 'a', 'y' => 'x'], false],
            'Only first parameter exists' => [['a', 'x'], ['a' => 'b'], false],
            'Only last parameter exists' => [['a', 'x'], ['x' => 'y'], false],
            'No expectation' => [[], ['a' => 'z', 'x' => 'y'], true],
        ];
    }
}
