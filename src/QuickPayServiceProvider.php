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
        $this->mergeConfigFrom(__DIR__ .'/../config/quikcpay.php', 'quickpay');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ .'/../quickpay.php' => config_path('quickpay.php')
        ], 'quickpay-config');
    }

    private function loadConfig()
    {
        return join(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            'config',
            'quickpay.php',
        ]);
    }
}
