# Laravel Request Logger

## Installation

### Composer

Add `prettus/laravel-request-logger` to the "require" section of your `composer.json` file.

```json
	"prettus/laravel-request-logger": "dev-master"
```

Run `composer update` to get the latest version of the package.

or 

Run `composer require prettus/laravel-request-logger` direct in your terminal

### Laravel

In your `config/app.php` add `'Prettus\RequestLogger\Providers\LoggerServiceProvider'` to the end of the `providers` array:

```php
'providers' => array(
    ...,
    'Prettus\RequestLogger\Providers\LoggerServiceProvider',
),
```

Publish Configuration

```shell
php artisan vendor:publish --provider="Prettus\RequestLogger\Providers\LoggerServiceProvider"
```

## Configuration

In your `config/request-logger.php` file, you can change configuration for logger


### Set Log Level

```php
'logger' => [
    'level' => 'info'
],
```

### Request Logger

```php
'request' => [
    'enabled' => true,
    'format'  => '{ip} {method} {url}'
],
```

### Response Logger

```php
'response' => [
    'enabled' => true,
    'format'  => '{ip} {method} {url} HTTP/{http_version} {status}'
]
```

### Format Interpolation

#### Request

- {method}
- {root}
- {url}
- {fullUrl}
- {path}
- {decodedPath}
- {ip}
- {format}
- {scheme}
- {port}
- {query_string}

*All the variables are available to Reponse*

#### Reponse

- {content}
- {status}
- {http_version}
- {is_cacheable}
- {is_empty}
- {is_client_error}
- {is_forbidden}
- {is_not_found}
- {is_ok}
- {is_invalid}
- {is_redirection}

## Examples

### Request Formats

`{method} {fullUrl}` 

```
[2015-04-03 00:00:00] local.INFO: GET http://prettus.local/user/1?param=lorem ["REQUEST"]
```

`{method} {fullUrl} {ip} {port}` 

```
[2015-04-03 00:00:00] local.INFO: GET http://prettus.local/user/1?param=lorem 192.168.10.1 80 ["REQUEST"]
```

`{method} {root} {url} {fullUrl} {path} {decodedPath} {ip} {format} {scheme} {port} {query_string}` 

```
[2015-04-03 00:00:00] local.INFO: GET http://prettus.local http://prettus.local/user/1 http://prettus.local/user/1?param=lorem user/1 user/1 192.168.10.1 html http 80 param=lorem ["REQUEST"]
```

### Response Formats

`[{status}] HTTP:{http_version} {content}`

```
[2015-04-03 00:00:00] local.INFO: [200] HTTP:1.1 {"id":1,"name":"Anderson Andrade", "email":"contato@andersonandra.de"} ["RESPONSE"]
```