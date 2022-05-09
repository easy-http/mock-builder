<?php

namespace EasyHttp\MockBuilder;

use EasyHttp\MockBuilder\Expectations\BodyExpectation;
use EasyHttp\MockBuilder\Expectations\HeaderExistsExpectation;
use EasyHttp\MockBuilder\Expectations\HeaderIsExpectation;
use EasyHttp\MockBuilder\Expectations\HeaderIsNotExpectation;
use EasyHttp\MockBuilder\Expectations\HeaderNotExistsExpectation;
use EasyHttp\MockBuilder\Expectations\MethodIsExpectation;
use EasyHttp\MockBuilder\Expectations\ParamExistsExpectation;
use EasyHttp\MockBuilder\Expectations\ParamIsExpectation;
use EasyHttp\MockBuilder\Expectations\ParamNotExistsExpectation;
use EasyHttp\MockBuilder\Expectations\PathIsExpectation;
use EasyHttp\MockBuilder\Expectations\PathMatchExpectation;
use EasyHttp\MockBuilder\Expectations\RequestExpectation;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class HttpMock
{
    private MockBuilder $builder;

    public function __construct(MockBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function __invoke(RequestInterface $request): FulfilledPromise
    {
        $rejectionReason = '';

        foreach ($this->builder->getExpectations() as $expectation) {
            $matches = true;

            $promise = new Promise(
                function () use (&$promise, $request) {
                    $promise->resolve($request);
                }
            );
            $promise
                ->then(MethodIsExpectation::from($expectation))
                ->then(PathIsExpectation::from($expectation))
                ->then(PathMatchExpectation::from($expectation))
                ->then(ParamIsExpectation::from($expectation))
                ->then(ParamExistsExpectation::from($expectation))
                ->then(ParamNotExistsExpectation::from($expectation))
                ->then(HeaderIsExpectation::from($expectation))
                ->then(HeaderIsNotExpectation::from($expectation))
                ->then(HeaderExistsExpectation::from($expectation))
                ->then(HeaderNotExistsExpectation::from($expectation))
                ->then(BodyExpectation::from($expectation))
                ->then(RequestExpectation::from($expectation))
                ->otherwise(
                    function ($reason) use (&$matches, &$rejectionReason) {
                        $rejectionReason = $reason;
                        $matches = false;
                    }
                );

            $promise->wait();

            if ($matches) {
                return $expectation->responseBuilder($request)->response();
            }
        }

        return $this->fallbackResponse($rejectionReason);
    }

    private function fallbackResponse($rejectionReason): FulfilledPromise
    {
        return new FulfilledPromise(
            new Response(404, [], $rejectionReason)
        );
    }
}
