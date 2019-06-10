<?php

namespace SureYee\LaravelIfcert;


use Illuminate\Support\ServiceProvider;

class CertServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/ifcert.php' => config_path('ifcert.php')
        ]);
    }
}