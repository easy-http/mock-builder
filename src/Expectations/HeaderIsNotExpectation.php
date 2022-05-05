<?php

namespace EasyHttp\MockBuilder\Expectations;

use EasyHttp\MockBuilder\Contracts\ExpectationMatcher;
use EasyHttp\MockBuilder\Expectation;
use EasyHttp\MockBuilder\Helpers\GuzzleHeaderParser;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;

class HeaderIsNotExpectation implements ExpectationMatcher
{
    public static function from(Expectation $expectation): callable
    {
        return function ($request) use ($expectation) {
            /** @var RequestInterface $request */
            $headers = GuzzleHeaderParser::parse($request->getHeaders());

            foreach ($expectation->rejectedHeadersIterator() as $header => $value) {
                if (array_key_exists($header, $headers) && $headers[$header] === $value) {
                    return new RejectedPromise('header \'' . $header . '\' is same from expectation');
                }
            }

            return $request;
        };
    }
}
