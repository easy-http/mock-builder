<p align="center"><img src="https://blog.pleets.org/img/articles/easy-http-logo.png" height="150"></p>

<p align="center">
<a href="https://travis-ci.com/easy-http/mock-builder"><img src="https://travis-ci.com/easy-http/mock-builder.svg?branch=main" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/easy-http/mock-builder"><img src="https://img.shields.io/scrutinizer/g/easy-http/mock-builder.svg" alt="Code Quality"></a>
<a href="https://scrutinizer-ci.com/g/easy-http/mock-builder/?branch=main"><img src="https://scrutinizer-ci.com/g/easy-http/mock-builder/badges/coverage.png?b=main" alt="Code Coverage"></a>
</p>

# Mock builder

A fluid interface to build HTTP mocks with an expressive syntax. You can use this library to build mocks for Guzzle, Symfony and other HTTP Clients.

<a href="https://sonarcloud.io/dashboard?id=easy-http_mock-builder"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_mock-builder&metric=security_rating" alt="Bugs"></a>
<a href="https://sonarcloud.io/dashboard?id=easy-http_mock-builder"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_mock-builder&metric=bugs" alt="Bugs"></a>
<a href="https://sonarcloud.io/dashboard?id=easy-http_mock-builder"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_mock-builder&metric=code_smells" alt="Bugs"></a>

# :pencil: Requirements

This library requires the following dependencies

- PHP 7.4, 8.0
- Guzzle v6, v7

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

## Expectations

| General                                                                               | URL                                                                                                      | Headers                                                                                          |
|---------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------|
| [pathIs](https://github.com/easy-http/mock-builder/wiki/Expectations#pathIs)          | [queryParamIs](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamIs)                 | [headerIs](https://github.com/easy-http/mock-builder/wiki/Expectations#headerIs)                 |
| [methodIs](https://github.com/easy-http/mock-builder/wiki/Expectations#methodIs)      | [queryParamExists](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamExists)         | [headerExists](https://github.com/easy-http/mock-builder/wiki/Expectations#headerExists)         |
|                                                                                       | [queryParamNotExist](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamNotExist)     | [headerNotExist](https://github.com/easy-http/mock-builder/wiki/Expectations#headerNotExist)     |
|                                                                                       | [queryParamsAre](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamsAre)             | [headersAre](https://github.com/easy-http/mock-builder/wiki/Expectations#headersAre)             |
|                                                                                       | [queryParamsExist](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamsExist)         | [headersExist](https://github.com/easy-http/mock-builder/wiki/Expectations#headersExist)         |
|                                                                                       | [queryParamsNotExist](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamsNotExist)   | [headersNotExist](https://github.com/easy-http/mock-builder/wiki/Expectations#headersNotExist)   |

Expectations for Regex

- [pathMatch](https://github.com/easy-http/mock-builder/wiki/Expectations#pathMatch)

## Responses

The following are all methods you can use to set up your HTTP response.

- [statusCode](https://github.com/easy-http/mock-builder/wiki/Responses#statusCode)
- [headers](https://github.com/easy-http/mock-builder/wiki/Responses#headers)
- [body](https://github.com/easy-http/mock-builder/wiki/Responses#body)
- [json](https://github.com/easy-http/mock-builder/wiki/Responses#json)
