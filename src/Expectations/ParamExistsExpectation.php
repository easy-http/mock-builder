<?php

namespace EasyHttp\MockBuilder\Expectations;

use EasyHttp\MockBuilder\Contracts\ExpectationMatcher;
use EasyHttp\MockBuilder\Expectation;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;

class ParamExistsExpectation implements ExpectationMatcher
{
    public static function from(Expectation $expectation): callable
    {
        return function ($request) use ($expectation) {
            /** @var RequestInterface $request */
            parse_str($request->getUri()->getQuery(), $params);

            foreach ($expectation->emptyQueryParamsIterator() as $param => $value) {
                if (!array_key_exists($param, $params)) {
                    return new RejectedPromise('param \'' . $param . '\' is missing');
                }
            }

            return $request;
        };
    }
}
