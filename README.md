# Laravel Request Logger

Request and Response Logger for Laravel

Insiperd by [Morgan - Node HTTP request logger](https://github.com/expressjs/morgan)

[![Latest Stable Version](https://poser.pugx.org/prettus/laravel-request-logger/v/stable.svg)](https://packagist.org/packages/prettus/laravel-request-logger) [![Total Downloads](https://poser.pugx.org/prettus/laravel-request-logger/downloads.svg)](https://packagist.org/packages/prettus/laravel-request-logger) [![Latest Unstable Version](https://poser.pugx.org/prettus/laravel-request-logger/v/unstable.svg)](https://packagist.org/packages/prettus/laravel-request-logger) [![License](https://poser.pugx.org/prettus/laravel-request-logger/license.svg)](https://packagist.org/packages/prettus/laravel-request-logger)
[![Analytics](https://ga-beacon.appspot.com/UA-61050740-1/laravel-request-logger/readme)](https://packagist.org/packages/prettus/laravel-request-logger)

## Installation

### Composer

Add `prettus/laravel-request-logger` to the "require" section of your `composer.json` file.

```json
	"prettus/laravel-request-logger": "1.0.*"
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

```php
 'logger' => [
    'enabled'   => true,
    'handlers'  => ['Prettus\RequestLogger\Handler\HttpLoggerHandler'],
    'file'      => storage_path("logs/http.log"),
    'level'     => 'info',
    'format'    => 'common'
]
```

| Property | Type       | Default Value                                         | Description |
|----------|------------|-------------------------------------------------------|-------------|
| enabled  | boolean    | true                                                  | Enable or disable log http |
| handlers | array      | ['Prettus\RequestLogger\Handler\HttpLoggerHandler']   | Instance of the `Monolog\Handler\HandlerInterface`. (See more)[https://github.com/Seldaek/monolog#handlers] |
| file     | string     | storage_path("logs/http.log")                         | If you are using `Prettus\RequestLogger\Handler\HttpLoggerHandler`, you can set the file will be saved walk logs |
| level    | string     | info                                                  | Level logger write: [notice, info, debug, emergency, alert, critical, error, warning] |
| format   | string     | common                                                | Format for the log record |



### Format Interpolation

#### Variables

| Format         | Description                                                           | Exemple                                 |
|----------------|-----------------------------------------------------------------------|-----------------------------------------|
| {method}       | Get the request method.                                               | PUT                                     |
| {root}         | Get the root URL for the application.                                 | http://prettus.local                    |
| {url}          | Get the URL (no query string) for the request.                        | http://prettus.local/users              |
| {full-url}      | Get the full URL for the request.                                     | http://prettus.local/users?search=lorem |
| {path}         | Get the current path info for the request.                            | /users                                  |
| {decoded-path}  | Get the current encoded path info for the request.                    | /users                                  |
| {remote-addr}  | Returns the client IP address.                                        | 192.168.10.1                            |
| {format}       | Gets the format associated with the mime type.                        | html                                    |
| {scheme}       | Gets the request's scheme.                                            | http                                    |
| {port}         | Returns the port on which the request is made.                        | 80                                      |
| {query-string} | Generates the normalized query string for the Request.                | ?search=lorem                           |
| {remote-user}  | Returns the user.                                                     |                                         |
| {referer}      | The page address (if any) by which the user agent to the current page |                                         |
| {user-agent}   | Get user agent                                                        | Mozilla/5.0 (Windows NT 6.3; WOW64)     |
| {date}         | Current Date                                                          | 2015-04-05 14:00:00                     |
| {content}        | Get the response content.       | {json:response} |
| {content-length} | Get the content length in bytes | 4863   |
| {response-time}  | Response time in ms             | 231             |
| {status}         | Http status code                | 200             |
| {http-version}   | Http protocol version           | 1.1             |
| {server[*KEY*]}   | $_SERVER Server and execution environment information (See more)[http://php.net/manual/reserved.variables.server.php]          |              |
| {req[*HEADER*]}   | Request Header values |              |
| {res[*HEADER*]}   | Response Header values |              |



#### Default formats

| Name      | Format                                                                                                                                |
|-----------|---------------------------------------------------------------------------------------------------------------------------------------|
| combined  | {remote-addr} - {remote-user} [{date}] "{method} {url} HTTP/{http-version}" {status} {content-length} "{referer}" "{user-agent}"     |
| common    | {remote-addr} - {remote-user} [{date}] "{method} {url} HTTP/{http-version}" {status} {content-length}                                 |
| dev       | {method} {url} {status} {response-time} ms - {content-length}                                                                         |
| short     | {remote-addr} {remote-user} {method} {url} HTTP/{http-version} {status} {content-length} - {response-time} ms                         |
| tiny      | {method} {url} {status} {content-length} - {response-time} ms                                                                         |


## Examples

`{method} {full-url}` 

```
[2015-04-03 00:00:00] local.INFO: GET http://prettus.local/user/1?param=lorem ["REQUEST"]
```

`{method} {full-url} {remote-addr} {port}` 

```
[2015-04-03 00:00:00] local.INFO: GET http://prettus.local/user/1?param=lorem 192.168.10.1 80 ["REQUEST"]
```

`{method} {root} {url} {full-url} {path} {decoded-path} {remote-addr} {format} {scheme} {port} {query-string}` 

```
[2015-04-03 00:00:00] local.INFO: GET http://prettus.local http://prettus.local/user/1 http://prettus.local/user/1?param=lorem user/1 user/1 192.168.10.1 html http 80 param=lorem ["REQUEST"]
```

`[{status}] HTTP:{http-version} {content}`

```
[2015-04-03 00:00:00] local.INFO: [200] HTTP:1.1 {"id":1,"name":"Anderson Andrade", "email":"contato@andersonandra.de"} ["RESPONSE"]
```