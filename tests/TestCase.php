<?php

namespace QuickPay\Tests;

use QuickPay\QuickPayServiceProvider;
use Illuminate\Contracts\Console\Kernel;

class TestCase extends \Orchestra\Testbench\TestCase {
    // use CreatesApplication;

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
