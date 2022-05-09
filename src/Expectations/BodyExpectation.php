<?php

namespace EasyHttp\MockBuilder\Expectations;

use EasyHttp\MockBuilder\Contracts\ExpectationMatcher;
use EasyHttp\MockBuilder\Expectation;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;

class BodyExpectation implements ExpectationMatcher
{
    public static function from(Expectation $expectation): callable
    {
        return function ($request) use ($expectation) {
            /** @var RequestInterface $request */
            $stream = $request->getBody();
            $stream->rewind();
            $body = $stream->getContents();

            foreach ($expectation->bodyHandlersIterator() as $handler) {
                if (!$handler($body)) {
                    return new RejectedPromise('body does not match expectation');
                }
            }

            return $request;
        };
    }
}
