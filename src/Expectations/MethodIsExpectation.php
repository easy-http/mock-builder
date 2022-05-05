<?php

namespace EasyHttp\MockBuilder\Expectations;

use EasyHttp\MockBuilder\Contracts\ExpectationMatcher;
use EasyHttp\MockBuilder\Expectation;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;

class MethodIsExpectation implements ExpectationMatcher
{
    public static function from(Expectation $expectation): callable
    {
        return function ($request) use ($expectation) {
            /** @var RequestInterface $request */
            if (!is_null($expectation->getMethod()) && $request->getMethod() !== $expectation->getMethod()) {
                return new RejectedPromise('method \'' . $request->getMethod() . '\' does not match expectation');
            }

            return $request;
        };
    }
}
