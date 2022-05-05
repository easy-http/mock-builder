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
            /** @var RequestInterface $request */
            if (!is_null($expectation->getPath()) && $request->getUri()->getPath() !== $expectation->getPath()) {
                return new RejectedPromise('path \'' . $request->getUri()->getPath() . '\' does not match expectation');
            }

            return $request;
        };
    }
}
