<?php

namespace EasyHttp\MockBuilder\Expectations;

use EasyHttp\MockBuilder\Expectation;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;

class PathMatchExpectation
{
    public static function from(Expectation $expectation): callable
    {
        return function ($request) use ($expectation) {
            /** @var RequestInterface $request */
            if (!is_null($expectation->getPathRegex()) && !self::matches($expectation, $request)) {
                return new RejectedPromise('path does not match expectation');
            }

            return $request;
        };
    }

    private static function matches($expectation, $request): bool
    {
        return preg_match($expectation->getPathRegex(), $request->getUri()->getPath(), $matches);
    }
}
