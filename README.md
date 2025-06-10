<p align="center"><img src="https://blog.pleets.org/img/articles/easy-http-logo-320.png"></p>

<p align="center">
<a href="https://github.com/easy-http/mock-builder/actions?query=workflow%3Atests"><img src="https://github.com/easy-http/mock-builder/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/easy-http/mock-builder"><img src="https://img.shields.io/scrutinizer/g/easy-http/mock-builder.svg" alt="Code Quality"></a>
<a href="https://sonarcloud.io/summary/overall?id=easy-http_mock-builder"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_mock-builder&metric=coverage" alt="Code Coverage"></a>
</p>
<p align="center">
    <a href="#tada-php-support" title="PHP Versions Supported"><img alt="PHP Versions Supported" src="https://img.shields.io/badge/php-7.4%20to%208.3-777bb3.svg?logo=php&logoColor=white&labelColor=555555"></a>
    <a href="https://packagist.org/packages/easy-http/mock-builder"><img src="https://img.shields.io/packagist/dt/easy-http/mock-builder" alt="Total Downloads"></a>
</p>

<p align="center">
    :rocket: Mock HTTP services
</p>

# Mock builder

A fluid interface to build HTTP mocks with an expressive syntax. You can use this library to build mocks for Guzzle, Symfony and other HTTP Clients.

<a href="https://sonarcloud.io/dashboard?id=easy-http_mock-builder"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_mock-builder&metric=security_rating" alt="Bugs"></a>
<a href="https://sonarcloud.io/dashboard?id=easy-http_mock-builder"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_mock-builder&metric=bugs" alt="Bugs"></a>
<a href="https://sonarcloud.io/dashboard?id=easy-http_mock-builder"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_mock-builder&metric=code_smells" alt="Bugs"></a>

This library supports the following versions of Guzzle Http Client.

<a href="#tada-php-support" title="Guzzle Version Supported"><img alt="PHP Versions Supported" src="https://img.shields.io/badge/guzzle-6.x-blue"></a>
<a href="#tada-php-support" title="Guzzle Version Supported"><img alt="PHP Versions Supported" src="https://img.shields.io/badge/guzzle-7.x-blue"></a>

# :wrench: Installation

Use following command to install this library:

```bash
composer require easy-http/mock-builder
```

# :bulb: Usage

## Creating a simple Mock for Guzzle

```php
use EasyHttp\MockBuilder\HttpMock;
use EasyHttp\MockBuilder\MockBuilder;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

$builder = new MockBuilder();
$builder
    ->when()
        ->pathIs('/v1/products')
        ->methodIs('POST')
    ->then()
        ->body('bar');

$mock = new HttpMock($builder);

$client = new Client(['handler' => HandlerStack::create($mock)]);
$client
    ->post('/v1/products')
    ->getBody()
    ->getContents(); // bar
```

:books: Check out the [Documentation](https://easy-http.com/docs) to learn how to use this library.
