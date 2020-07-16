<?php

namespace QuickPay;

use Illuminate\Support\ServiceProvider;
use QuickPay\Facades\Ping;

class QuickPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ping', function ($app) {
            return new Ping();
        });
    }

    public function boot()
    {
        $this->mergeConfigFrom($this->loadConfig(), 'quickpay');
    }

    private loadConfig() {
        return join(DIRECTORY_SEPARATOR, array(
            __DIR__,
            '..',
            'config',
            'config.php'
        ));
    }
}
