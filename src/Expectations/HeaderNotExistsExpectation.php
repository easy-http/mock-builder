<?php

namespace EasyHttp\MockBuilder\Expectations;

use EasyHttp\MockBuilder\Contracts\ExpectationMatcher;
use EasyHttp\MockBuilder\Expectation;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;

class HeaderNotExistsExpectation implements ExpectationMatcher
{
    public static function from(Expectation $expectation): callable
    {
        return function ($request) use ($expectation) {
            /** @var RequestInterface $request */

            foreach ($expectation->missingHeadersIterator() as $header) {
                if ($request->hasHeader($header)) {
                    return new RejectedPromise('header ' . $header . ' is present');
                }
            }

            return $request;
        };
    }
}