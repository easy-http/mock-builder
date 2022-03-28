<p align="center"><img src="https://blog.pleets.org/img/articles/easy-http-logo-320.png"></p>

<p align="center">
<a href="https://github.com/easy-http/mock-builder/actions?query=workflow%3Atests"><img src="https://github.com/easy-http/mock-builder/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/easy-http/mock-builder"><img src="https://img.shields.io/scrutinizer/g/easy-http/mock-builder.svg" alt="Code Quality"></a>
<a href="https://sonarcloud.io/summary/overall?id=easy-http_mock-builder"><img src="https://sonarcloud.io/api/project_badges/measure?project=easy-http_mock-builder&metric=coverage" alt="Code Coverage"></a>
</p>
<p align="center">
  <a href="https://stand-with-ukraine.pp.ua" title="#StandWithUkraine"><img alt="#StandWithUkraine" src="https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/StandWithUkraine.svg"></a>
</p>
<p align="center">
    <a href="#tada-php-support" title="PHP Versions Supported"><img alt="PHP Versions Supported" src="https://img.shields.io/badge/php-7.4%20to%208.2-777bb3.svg?logo=php&logoColor=white&labelColor=555555"></a>
</p>

<p align="center">
    :rocket: Change easily from one client to another using http layer contracts
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

## Expectations

### Single Value

| General                                                                               | URL                                                                                                      | Headers                                                                                          |
|---------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------|
| [pathIs](https://github.com/easy-http/mock-builder/wiki/Expectations#pathIs)          | [queryParamIs](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamIs)                 | [headerIs](https://github.com/easy-http/mock-builder/wiki/Expectations#headerIs)                 |
| [methodIs](https://github.com/easy-http/mock-builder/wiki/Expectations#methodIs)      | [queryParamExists](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamExists)         | [headerIsNot](https://github.com/easy-http/mock-builder/wiki/Expectations#headerIsNot)           |
|                                                                                       | [queryParamNotExist](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamNotExist)     | [headerExists](https://github.com/easy-http/mock-builder/wiki/Expectations#headerExists)         |
|                                                                                       |                                                                                                          | [headerNotExist](https://github.com/easy-http/mock-builder/wiki/Expectations#headerNotExist)     |

### Multi Value

| URL                                                                                                      | Headers                                                                                          |
|----------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------|
| [queryParamsAre](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamsAre)             | [headersAre](https://github.com/easy-http/mock-builder/wiki/Expectations#headersAre)             |
| [queryParamsExist](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamsExist)         | [headersExist](https://github.com/easy-http/mock-builder/wiki/Expectations#headersExist)         |
| [queryParamsNotExist](https://github.com/easy-http/mock-builder/wiki/Expectations#queryParamsNotExist)   | [headersNotExist](https://github.com/easy-http/mock-builder/wiki/Expectations#headersNotExist)   |

Expectations for Regex

- [pathMatch](https://github.com/easy-http/mock-builder/wiki/Expectations#pathMatch)

## Responses

The following are all methods you can use to set up your HTTP response.

- [statusCode](https://github.com/easy-http/mock-builder/wiki/Responses#statusCode)
- [headers](https://github.com/easy-http/mock-builder/wiki/Responses#headers)
- [body](https://github.com/easy-http/mock-builder/wiki/Responses#body)
- [json](https://github.com/easy-http/mock-builder/wiki/Responses#json)
