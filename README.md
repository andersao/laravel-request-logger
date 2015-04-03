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