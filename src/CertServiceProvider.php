<?php

namespace Sureyee\LaravelIfcert;

use Illuminate\Support\ServiceProvider;

class CertServiceProvider extends ServiceProvider
{

    public function register()
    {

    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/ifcert.php' => config_path('ifcert.php'),
        ], 'ifcert-config');

        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'ifcert-migrations');

        Client::setEnv(env('APP_ENV'));
    }
}