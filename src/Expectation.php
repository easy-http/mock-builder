<?php

namespace EasyHttp\MockBuilder;

use EasyHttp\MockBuilder\Contracts\HeaderAggregate;
use EasyHttp\MockBuilder\Contracts\QueryParameterAggregate;
use EasyHttp\MockBuilder\Contracts\ResponseBuilder;
use EasyHttp\MockBuilder\Iterators\ArrayIterator;
use EasyHttp\MockBuilder\Iterators\NotEmptyArrayValuesIterator;
use EasyHttp\MockBuilder\Iterators\EmptyArrayValuesIterator;
use EasyHttp\MockBuilder\ResponseBuilders\GuzzleResponseBuilder;

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

    public function then(): ResponseBuilder
    {
        $this->responseBuilder = new GuzzleResponseBuilder();

        return $this->responseBuilder;
    }

    public function responseBuilder(): ResponseBuilder
    {
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

    public function queryParamNotExists(string $key): self
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

    public function queryParamsExists(array $params): self
    {
        array_walk(
            $params,
            function ($value) {
                $this->queryParamExists($value);
            }
        );

        return $this;
    }

    public function queryParamsNotExists(array $params): self
    {
        array_walk(
            $params,
            function ($value) {
                $this->queryParamNotExists($value);
            }
        );

        return $this;
    }

    public function headerIs(string $key, string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function headerExists(string $key): self
    {
        $this->headers[$key] = null;

        return $this;
    }

    public function headerNotExists(string $key): self
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

    public function headersExists(array $headers): self
    {
        array_walk(
            $headers,
            function ($value) {
                $this->headerExists($value);
            }
        );

        return $this;
    }

    public function headersNotExists(array $headers): self
    {
        array_walk(
            $headers,
            function ($value) {
                $this->headerNotExists($value);
            }
        );

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
}
