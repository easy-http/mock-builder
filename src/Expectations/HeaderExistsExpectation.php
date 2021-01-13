<?php

namespace EasyHttp\MockBuilder\Expectations;

use EasyHttp\MockBuilder\Contracts\ExpectationMatcher;
use EasyHttp\MockBuilder\Expectation;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;

class HeaderExistsExpectation implements ExpectationMatcher
{
    public static function from(Expectation $expectation): callable
    {
        return function ($request) use ($expectation) {
            /** @var RequestInterface $request */

            foreach ($expectation->emptyHeadersIterator() as $param => $value) {
                if (!$request->hasHeader($param)) {
                    return new RejectedPromise('header ' . $param . ' is not present');
                }
            }

            return $request;
        };
    }
}
