
# This is the client Integration with ApiTwist SSO.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/brnysn/apitwist-sso.svg?style=flat-square)](https://packagist.org/packages/brnysn/apitwist-sso)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/brnysn/apitwist-sso/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/brnysn/apitwist-sso/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/brnysn/apitwist-sso.svg?style=flat-square)](https://packagist.org/packages/brnysn/apitwist-sso)

Client integration for ApiTwist SSO.

## Installation

Install the package via composer:

```bash
composer require brnysn/apitwist-sso
```

Add SSO config to your `.env` file:

```php
SSO_CLIENT_ID=client_id
SSO_CLIENT_SECRET=client_secret
SSO_REDIRECT_URL='https://yourdomain.com/sso/callback/'
```

Add HasSsoTokens trait to your User model

```php
use Brnysn\ApitwistSso\Traits\HasSsoTokens;
```

Add middlewares to your `app/Http/Kernel.php` file $routeMiddleware array:

```php
protected $routeMiddleware = [
    // ...
    'sso.auth' => \Brnysn\ApiTwistSSO\Http\Middleware\SsoAuthenticate::class,
    'sso.api' => \Brnysn\ApiTwistSSO\Http\Middleware\SsoApiAuthenticate::class,
];
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
    'client_id' => env('SSO_CLIENT_ID'),
    'client_secret' => env('SSO_CLIENT_SECRET'),
    'redirect_url' => env('SSO_REDIRECT_URL'),
    'authorize_url' => env('SSO_API_AUTHORIZE_URL', 'https://sso.apitwist.com/oauth/authorize'),
    'api_url' => env('SSO_API_URL', 'https://sso.apitwist.com/oauth/token'),
    'logout_url' => env('SSO_API_LOGOUT_URL', 'https://sso.apitwist.com/logout'),
    'get_user_url' => env('SSO_API_GET_USER_URL', 'https://sso.apitwist.com/api/user'),
];
```

## Usage

Use `sso.auth` along with `web` middleware in your `routes/web.php` file:

```php
Route::group([ 'middleware' => [ 'web', 'sso.auth' ] ], function () {
    // Your routes
});
```

If you see `Session store not set on request.` error, add theese middlewares in your `app/Http/Kernel.php` file $middleware array:

```php
protected $middleware = [
    // ...
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
];
```


Use named routes as authentication routes:

```php
sso.login
sso.logout
```

## Credits

- [Yasin BARAN](https://github.com/brnysn)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
