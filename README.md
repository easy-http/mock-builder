<p align="center"><img src="https://blog.pleets.org/img/articles/easy-http-logo.png" height="150"></p>

# Mock builder

A fluid interface to build HTTP mocks. You can use this library to build mock for Guzzle, Symfony and other HTTP Clients.

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

| Method                                          | Name                                  |
|-------------------------------------------------|---------------------------------------|
| `methodIs(string $method)`                      | Expects for a method                  |
| `queryParamIs(string $key, string $value)`      | Expects for a specific query param    |
