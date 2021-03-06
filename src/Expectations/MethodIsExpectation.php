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
            if ($method = $expectation->getMethod()) {
                /** @var RequestInterface $request */
                if ($request->getMethod() !== $method) {
                    return new RejectedPromise('method does not match expectation');
                }
            }

            return $request;
        };
    }
}
