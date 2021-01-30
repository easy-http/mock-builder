<p align="center"><img src="https://blog.pleets.org/img/articles/easy-http-logo.png" height="150"></p>

# Mock builder

A fluid interface to build HTTP mocks. You can use this library to build mocks for Guzzle, Symfony and other HTTP Clients.

# :pencil: Requirements

This library requires the following dependencies

- Guzzle v7.0 or later

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

| General                    | URL                                           | Headers                               |
|----------------------------|-----------------------------------------------|---------------------------------------|
| [pathIs](#pathIs)          | [queryParamIs](#queryParamIs)                 | [headerIs](#headerIs)                 |
| [methodIs](#methodIs)      | [queryParamExists](#queryParamExists)         | [headerExists](#headerExists)         |
|                            | [queryParamNotExists](#queryParamNotExists)   | [headerNotExists](#headerNotExists)   |
|                            | [queryParamsAre](#queryParamsAre)             | [headersAre](#headersAre)             |
|                            | [queryParamsExists](#queryParamsExists)       | [headersExists](#headersExists)       |
|                            | [queryParamsNotExists](#queryParamsNotExists) | [headersNotExists](#headersNotExists) |

### pathIs

It expects for a path.

```php
$builder
    ->when()
        ->pathIs('/v1/products')
    ->then()
        ...
```

### methodIs

It expects for a method.

```php
$builder
    ->when()
        ->methodIs('POST')
    ->then()
        ...
```

### queryParamIs

It expects for a query parameter with specific value (for example term=bluebird).

```php
$builder
    ->when()
        ->queryParamIs('page', '5')
    ->then()
        ...
```

### queryParamExists

It expects a query parameter exists.

```php
$builder
    ->when()
        ->queryParamExists('pages')
    ->then()
        ...
```

### queryParamNotExists

It expects a query parameter does not exists.

```php
$builder
    ->when()
        ->queryParamNotExists('pages')
    ->then()
        ...
```

### queryParamsAre

It expects for a query parameters set with specific values.

```php
$builder
    ->when()
        ->queryParamsAre([
            'pages' => '30',
            'npage' => '5'
        ])
    ->then()
        ...
```

### queryParamsExists

It expects a query parameters set exists.

```php
$builder
    ->when()
        ->queryParamsExists([
            'pages',
            'npage'
        ])
    ->then()
        ...
```

### queryParamsNotExists

It expects a query parameters set does not exists.

```php
$builder
    ->when()
        ->queryParamsNotExists([
            'pages',
            'npage'
        ])
    ->then()
        ...
```

### headerIs

It expects for a header with specific value (for example Content-Type: text/html).

```php
$builder
    ->when()
        ->headerIs('Content-Type', 'text/html')
    ->then()
        ...
```

### headerExists

It expects a header exists.

```php
$builder
    ->when()
        ->headerExists('Authorization')
    ->then()
        ...
```

### headerNotExists

It expects a header does not exist.

```php
$builder
    ->when()
        ->headerNotExists('Authorization')
    ->then()
        ...
```

### headersAre

It expects for a headers set with specific values.

```php
$builder
    ->when()
        ->headersAre([
            'Authorization' => 'Bearer YourToken',
            'Content-Type' => 'application/json'
        ])
    ->then()
        ...
```

### headersExists

It expects a headers set exists.

```php
$builder
    ->when()
        ->headersExists([
            'Authorization',
            'Content-Type'
        ])
    ->then()
        ...
```

### headersNotExists

It expects a headers set does not exists.

```php
$builder
    ->when()
        ->headersNotExists([
            'Authorization',
            'Content-Type'
        ])
    ->then()
        ...
```

## Responses

The following are all methods you can use to set up your HTTP response.

- [statusCode](#statusCode)
- [body](#body)
- [json](#json)

### statusCode

It sets the status code.

```php
$builder
    ->when()
        ...
    ->then()
        ->statusCode(500);
```

### body

It sets the body.

```php
$builder
    ->when()
        ...
    ->then()
        ->body('hello world');
```

### json

It sets the json response.

```php
$builder
    ->when()
        ...
    ->then()
        ->json(['foo' => 'bar']);
```