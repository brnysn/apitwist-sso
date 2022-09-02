
# This is the client Integration with ApiTwist SSO.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/brnysn/apitwist-sso.svg?style=flat-square)](https://packagist.org/packages/brnysn/apitwist-sso)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/brnysn/apitwist-sso/run-tests?label=tests)](https://github.com/brnysn/apitwist-sso/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/brnysn/apitwist-sso/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/brnysn/apitwist-sso/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/brnysn/apitwist-sso.svg?style=flat-square)](https://packagist.org/packages/brnysn/apitwist-sso)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require brnysn/apitwist-sso
```

Add SSO config to your `.env` file:

```php
client_id=client_id
client_secret=client_secret
redirect_url='https://yourdomain.com//sso/callback/'
```

Add HasSsoTokens trait to your User model

```php
use Brnysn\ApitwistSso\Traits\HasSsoTokens;
```

Add middlewares to your `app/Http/Kernel.php` file:

```php
'sso.auth' => \App\Http\Middleware\SsoAuthenticate::class,
'sso.api' => \App\Http\Middleware\SsoApiAuthenticate::class,
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="apitwist-sso-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="apitwist-sso-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="apitwist-sso-views"
```

## Usage

Use `sso.auth` middleware in your `routes/web.php` file:

```php
Route::group([ 'middleware' => [ 'web', 'sso.auth' ] ], function () {
    // Your routes
});
```

Use named routes as authentication routes:

```php
sso.login
sso.logout
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Yasin BARAN](https://github.com/brnysn)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
