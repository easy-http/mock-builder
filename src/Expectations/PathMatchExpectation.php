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
            if ($regex = $expectation->getPathRegex()) {
                /** @var RequestInterface $request */
                if (!preg_match($regex, $request->getUri()->getPath(), $matches)) {
                    return new RejectedPromise('path does not match expectation');
                }
            }

            return $request;
        };
    }
}
