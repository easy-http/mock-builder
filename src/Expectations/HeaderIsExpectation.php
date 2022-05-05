<?php

namespace EasyHttp\MockBuilder\Expectations;

use EasyHttp\MockBuilder\Helpers\GuzzleHeaderParser;
use EasyHttp\MockBuilder\Contracts\ExpectationMatcher;
use EasyHttp\MockBuilder\Expectation;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;

class HeaderIsExpectation implements ExpectationMatcher
{
    public static function from(Expectation $expectation): callable
    {
        return function ($request) use ($expectation) {
            /** @var RequestInterface $request */
            $headers = GuzzleHeaderParser::parse($request->getHeaders());

            foreach ($expectation->notEmptyHeadersIterator() as $header => $value) {
                if (!array_key_exists($header, $headers)) {
                    return new RejectedPromise('header \'' . $header . '\' is missing');
                }

                if ($headers[$header] !== $value) {
                    return new RejectedPromise('header \'' . $header . '\' does not match expectation');
                }
            }

            return $request;
        };
    }
}
