<?php

namespace QuickPay\Tests;

use QuickPay\QuickPayServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase {
    public function setup(): void {
        parent::setup();
    }

    protected function getPackageProviders($app) {
        return [
            QuickPayServiceProvider::class
        ];    
    }

    protected function getEnvironmentSetUp($app) {
    }
}
