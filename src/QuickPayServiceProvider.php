<?php

namespace QuickPay;

use Illuminate\Support\ServiceProvider;
use QuickPay\Facades\Ping;
use QuickPay\Facades\Changelog;

class QuickPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ping', function ($app) {
            return new Ping();
        });
        $this->app->bind('changelog', function ($app) {
            return new Changelog();
        });
    }

    public function boot()
    {
        $this->publishes([
            $this->loadConfig() => config_path('quickpay.php'),
        ]);
    }

    private function loadConfig()
    {
        return join(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            'config',
            'config.php',
        ]);
    }
}
