<?php

namespace Sureyee\LaravelIfcert;

use Illuminate\Support\ServiceProvider;
use Sureyee\LaravelIfcert\Commands\IfcertReport;

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

        $this->commands([
            IfcertReport::class
        ]);

        Client::setEnv(env('APP_ENV'));
    }
}