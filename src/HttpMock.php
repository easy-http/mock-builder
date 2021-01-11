<?php

namespace EasyHttp\MockBuilder;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\RejectedPromise;
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

            $promise = new Promise(function() use (&$promise, $request) {
                $promise->resolve($request);
            });
            $promise->then(
                function ($request) use ($expectation) {
                    if ($method = $expectation->getMethod()) {
                        if ($request->getMethod() !== $method) {
                            return new RejectedPromise('method does not match expectation');
                        }
                    }

                    return $request;
                }
            )->then(
                function ($request) use ($expectation) {
                    parse_str($request->getUri()->getQuery(), $params);

                    foreach ($expectation->getQueryParams() as $param => $value) {
                        if (!array_key_exists($param, $params)) {
                            return new RejectedPromise('param ' . $param . ' is not present');
                        }

                        if ($params[$param] !== $value) {
                            return new RejectedPromise('param ' . $param . ' is different from expectation');
                        }
                    }

                    return $request;
                }
            )->otherwise(
                function() use (&$matches) {
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
