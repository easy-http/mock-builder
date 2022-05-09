<?php

namespace EasyHttp\MockBuilder\Expectations;

use EasyHttp\MockBuilder\Expectation;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;

class RequestExpectation
{
    public static function from(Expectation $expectation): callable
    {
        return function ($request) use ($expectation) {
            /** @var RequestInterface $request */
            foreach ($expectation->requestHandlersIterator() as $handler) {
                if (!$handler($request)) {
                    return new RejectedPromise('request does not match expectation');
                }
            }

            return $request;
        };
    }
}
