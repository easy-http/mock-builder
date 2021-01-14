<?php

namespace EasyHttp\MockBuilder\Expectations;

use EasyHttp\MockBuilder\Contracts\ExpectationMatcher;
use EasyHttp\MockBuilder\Expectation;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;

class PathIsExpectation implements ExpectationMatcher
{
    public static function from(Expectation $expectation): callable
    {
        return function ($request) use ($expectation) {
            if ($path = $expectation->getPath()) {
                /** @var RequestInterface $request */
                if ($request->getUri()->getPath() !== $path) {
                    return new RejectedPromise('path does not match expectation');
                }
            }

            return $request;
        };
    }
}
