<?php

namespace EasyHttp\MockBuilder;

use EasyHttp\MockBuilder\Expectations\HeaderExistsExpectation;
use EasyHttp\MockBuilder\Expectations\HeaderIsExpectation;
use EasyHttp\MockBuilder\Expectations\HeaderNotExistsExpectation;
use EasyHttp\MockBuilder\Expectations\MethodIsExpectation;
use EasyHttp\MockBuilder\Expectations\ParamExistsExpectation;
use EasyHttp\MockBuilder\Expectations\ParamIsExpectation;
use EasyHttp\MockBuilder\Expectations\ParamNotExistsExpectation;
use EasyHttp\MockBuilder\Expectations\PathIsExpectation;
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
                ->then(ParamIsExpectation::from($expectation))
                ->then(ParamExistsExpectation::from($expectation))
                ->then(ParamNotExistsExpectation::from($expectation))
                ->then(HeaderIsExpectation::from($expectation))
                ->then(HeaderExistsExpectation::from($expectation))
                ->then(HeaderNotExistsExpectation::from($expectation))
                ->otherwise(
                    function () use (&$matches) {
                        $matches = false;
                    }
                );

            $promise->wait();

            if ($matches) {
                return $expectation->responseBuilder()->response();
            }
        }

        return $this->fallbackResponse();
    }

    private function fallbackResponse(): FulfilledPromise
    {
        return new FulfilledPromise(
            new Response(404)
        );
    }
}
