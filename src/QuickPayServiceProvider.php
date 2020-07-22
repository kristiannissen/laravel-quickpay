<?php

namespace QuickPay;

use Illuminate\Support\ServiceProvider;

class QuickPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom($this->loadConfig(), 'quickpay');
    }

    public function boot()
    {
        $this->publishes(
            [
                $this->loadConfig() => config_path('quickpay.php'),
            ],
            'quickpay-config'
        );
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
