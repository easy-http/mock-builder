<?php

namespace EasyHttp\MockBuilder;

use EasyHttp\MockBuilder\Contracts\HeaderAggregate;
use EasyHttp\MockBuilder\Contracts\QueryParameterAggregate;
use EasyHttp\MockBuilder\Contracts\ResponseBuilder;
use EasyHttp\MockBuilder\Iterators\ArrayIterator;
use EasyHttp\MockBuilder\Iterators\NotEmptyArrayValuesIterator;
use EasyHttp\MockBuilder\Iterators\EmptyArrayValuesIterator;
use EasyHttp\MockBuilder\ResponseBuilders\GuzzleResponseBuilder;
use Psr\Http\Message\RequestInterface;

class Expectation implements QueryParameterAggregate, HeaderAggregate
{
    protected ResponseBuilder $responseBuilder;

    private string $method;
    private string $path;
    private array $queryParams = [];
    private array $headers = [];

    private string $pathRegex;
    private array $missingQueryParams = [];
    private array $missingHeaders = [];

    private array $rejectedHeaders = [];

    private array $bodyHandlers = [];
    private array $requestHandlers = [];

    public function then(): ResponseBuilder
    {
        $this->responseBuilder = new GuzzleResponseBuilder();

        return $this->responseBuilder;
    }

    public function responseBuilder(?RequestInterface $request = null): ResponseBuilder
    {
        $this->responseBuilder->setRequest($request);

        return $this->responseBuilder;
    }

    public function getMethod(): ?string
    {
        return $this->method ?? null;
    }

    public function getPath(): ?string
    {
        return $this->path ?? null;
    }

    public function getPathRegex(): ?string
    {
        return $this->pathRegex ?? null;
    }

    public function methodIs(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function pathIs(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function pathMatch(string $regex): self
    {
        $this->pathRegex = $regex;

        return $this;
    }

    public function queryParamIs(string $key, string $value): self
    {
        $this->queryParams[$key] = $value;

        return $this;
    }

    public function queryParamExists(string $key): self
    {
        $this->queryParams[$key] = null;

        return $this;
    }

    public function queryParamNotExist(string $key): self
    {
        $this->missingQueryParams[] = $key;

        return $this;
    }

    public function queryParamsAre(array $params): self
    {
        array_walk(
            $params,
            function ($value, $key) {
                $this->queryParamIs($key, $value);
            }
        );

        return $this;
    }

    public function queryParamsExist(array $params): self
    {
        array_walk(
            $params,
            function ($value) {
                $this->queryParamExists($value);
            }
        );

        return $this;
    }

    public function queryParamsNotExist(array $params): self
    {
        array_walk(
            $params,
            function ($value) {
                $this->queryParamNotExist($value);
            }
        );

        return $this;
    }

    public function headerIs(string $key, string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function headerIsNot(string $key, string $value): self
    {
        $this->rejectedHeaders[$key] = $value;

        return $this;
    }

    public function headerExists(string $key): self
    {
        $this->headers[$key] = null;

        return $this;
    }

    public function headerNotExist(string $key): self
    {
        $this->missingHeaders[] = $key;

        return $this;
    }

    public function headersAre(array $headers): self
    {
        array_walk(
            $headers,
            function ($value, $key) {
                $this->headerIs($key, $value);
            }
        );

        return $this;
    }

    public function headersExist(array $headers): self
    {
        array_walk(
            $headers,
            function ($value) {
                $this->headerExists($value);
            }
        );

        return $this;
    }

    public function headersNotExist(array $headers): self
    {
        array_walk(
            $headers,
            function ($value) {
                $this->headerNotExist($value);
            }
        );

        return $this;
    }

    public function body(callable $callback): self
    {
        $this->bodyHandlers[] = $callback;

        return $this;
    }

    public function request(callable $callback): self
    {
        $this->requestHandlers[] = $callback;

        return $this;
    }

    public function notEmptyQueryParamsIterator(): NotEmptyArrayValuesIterator
    {
        return new NotEmptyArrayValuesIterator($this->queryParams);
    }

    public function emptyQueryParamsIterator(): EmptyArrayValuesIterator
    {
        return new EmptyArrayValuesIterator($this->queryParams);
    }

    public function missingQueryParamsIterator(): ArrayIterator
    {
        return new ArrayIterator($this->missingQueryParams);
    }

    public function notEmptyHeadersIterator(): NotEmptyArrayValuesIterator
    {
        return new NotEmptyArrayValuesIterator($this->headers);
    }

    public function emptyHeadersIterator(): EmptyArrayValuesIterator
    {
        return new EmptyArrayValuesIterator($this->headers);
    }

    public function missingHeadersIterator(): ArrayIterator
    {
        return new ArrayIterator($this->missingHeaders);
    }

    public function rejectedHeadersIterator(): ArrayIterator
    {
        return new ArrayIterator($this->rejectedHeaders);
    }

    public function bodyHandlersIterator(): ArrayIterator
    {
        return new ArrayIterator($this->bodyHandlers);
    }

    public function requestHandlersIterator(): ArrayIterator
    {
        return new ArrayIterator($this->requestHandlers);
    }
}
