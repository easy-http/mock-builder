<p align="center"><img src="https://blog.pleets.org/img/articles/easy-http-logo.png" height="150"></p>

# Mock builder

A fluid interface to build HTTP mocks. You can use this library to build mocks for Guzzle, Symfony and other HTTP Clients.

# Requirements

This library requires the following dependencies

- Guzzle v7.0 or later

# Installation

Use following command to install this library:

```bash
composer require easy-http/mock-builder
```

# Usage

## Creating a simple Mock for Guzzle

```php
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

$builder = new MockBuilder();
$builder
    ->when()
        ->methodIs('POST')
    ->then()
        ->body('bar');

$mock = new HttpMock($builder);

$client = new Client(['handler' => HandlerStack::create($mock)]);
$client
    ->post('foo')
    ->getBody()
    ->getContents(); // bar
```

## Expectations

`methodIs(string $method)`

Expects for a method.

### URL

There are a few expectations for URL components. Remember a URL is a Uniform Resource Locator
that locates an existing resource on the Internet. A URL for HTTP (or HTTPS) is normally made up of three or four components:

- A schema
- A host
- A path
- A query string

In this way, you can use the following expectations for URL matching.

`queryParamIs(string $key, string $value)`

Expects for a query parameter with specific value (for example term=bluebird).

`queryParamExists(string $param)`

Expects a query parameter exists.

`queryParamNotExists(string $param)`

Expects a query parameter does not exists.

`queryParamsAre(array $params)`

Expects for a query parameter set with specific values.

`queryParamsExists(array $params)`

Expects a query parameter set exists.

`queryParamsNotExists(array $params)`

Expects a query parameter set does not exists.

### Headers

`headerIs(string $key, string $value)`

Expects for a header with specific value (for example Content-Type: text/html).