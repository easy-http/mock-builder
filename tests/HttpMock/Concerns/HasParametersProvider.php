<?php

namespace EasyHttp\MockBuilder\Tests\HttpMock\Concerns;

trait HasParametersProvider
{
    /**
     * This parameters can be use as query parameters or headers
     *
     * @return array[]
     */
    public function paramsProvider(): array
    {
        return [
            'Same parameter expectation and parameter' => [['foo' => 'bar'], ['foo' => 'bar'], true],
            'Same parameters expectation and parameter' => [['a' => 'b', 'x' => 'y'], ['a' => 'b', 'x' => 'y'], true],
            'Same parameter expectation and different parameter value' => [['foo' => 'bar'], ['foo' => 'baz'], false],
            'Different parameter expectation and values' => [['foo' => 'bar'], ['bar' => 'baz'], false],
            'Match only first parameter' => [['a' => 'b', 'x' => 'y'], ['a' => 'b', 'x' => 'z'], false],
            'Match only last parameter' => [['a' => 'b', 'x' => 'y'], ['a' => 'z', 'x' => 'y'], false],
            'No expectation' => [[], ['a' => 'z', 'x' => 'y'], true],
        ];
    }

    /**
     * This parameters can be use as query parameters or headers
     *
     * @return array[]
     */
    public function existingParamsProvider(): array
    {
        return [
            'Parameter exists in expectation' => [['foo'], ['foo' => 'bar'], true],
            'Parameters exists in expectation' => [['a', 'x'], ['a' => 'b', 'x' => 'y'], true],
            'Parameter does not exists in expectation' => [['foo'], ['bar' => 'baz'], false],
            'Parameters does not exists in expectation' => [['a', 'x'], ['b' => 'a', 'y' => 'x'], false],
            'Only first parameter exists' => [['a', 'x'], ['a' => 'b'], false],
            'Only last parameter exists' => [['a', 'x'], ['x' => 'y'], false],
            'No expectation' => [[], ['a' => 'z', 'x' => 'y'], true],
        ];
    }

    /**
     * This parameters can be use as query parameters or headers
     *
     * @return array[]
     */
    public function notExistingParamsProvider(): array
    {
        return [
            'Parameter exists in expectation' => [['foo'], ['foo' => 'bar'], false],
            'Parameters exists in expectation' => [['a', 'x'], ['a' => 'b', 'x' => 'y'], false],
            'Parameter does not exists in expectation' => [['foo'], ['bar' => 'baz'], true],
            'Parameters does not exists in expectation' => [['a', 'x'], ['b' => 'a', 'y' => 'x'], true],
            'Only first parameter exists' => [['a', 'x'], ['a' => 'b'], false],
            'Only last parameter exists' => [['a', 'x'], ['x' => 'y'], false],
            'No expectation' => [[], ['a' => 'z', 'x' => 'y'], true],
        ];
    }
}
