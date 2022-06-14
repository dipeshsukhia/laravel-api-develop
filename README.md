# Develop(Export) Laravel REST apis

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dipeshsukhia/laravel-api-develop.svg?style=flat-square)](https://packagist.org/packages/dipeshsukhia/laravel-api-develop)
[![Total Downloads](https://img.shields.io/packagist/dt/dipeshsukhia/laravel-api-develop.svg?style=flat-square)](https://packagist.org/packages/dipeshsukhia/laravel-api-develop)
![GitHub Actions](https://github.com/dipeshsukhia/laravel-api-develop/actions/workflows/main.yml/badge.svg)

Develop(Export) Laravel REST apis with form request, api resources  and collections.

## Installation

You can install the package via composer:

```bash
composer require dipeshsukhia/laravel-api-develop
```

## Usage

```php
php artisan develop-api:install
```
use trait in App\Exceptions\Handler.php

```php
use App\Exceptions\Traits\ApiHandlerTrait;

class Handler extends ExceptionHandler
{
    use ApiHandlerTrait;
```

```php
php artisan develop-api --model=User
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dipesh.sukhia@gmail.com instead of using the issue tracker.

## Credits

-   [Dipesh Sukhia](https://github.com/dipeshsukhia)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
