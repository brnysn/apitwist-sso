<?php

namespace Brnysn\ApiTwistSSO;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ApiTwistSSOServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('apitwist-sso')
            ->hasConfigFile()
            ->hasMigration('create_sso_tokens_table')
            ->hasRoute('web');
    }
}
