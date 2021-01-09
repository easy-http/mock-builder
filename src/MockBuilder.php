<?php

namespace EasyHttp\MockBuilder;

class MockBuilder
{
    /**
     * @var array Expectation[]
     */
    protected array $expectations;

    public function when(): Expectation
    {
        $expectation = new Expectation();
        $this->expectations[] = $expectation;

        return $expectation;
    }

    /**
     * @return Expectation[]
     */
    public function getExpectations(): array
    {
        return $this->expectations;
    }
}
