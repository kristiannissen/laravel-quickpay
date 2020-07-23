<?php

namespace QuickPay\Tests\Unit;

use QuickPay\Tests\TestCase;
use QuickPay\Acquirer\AcquirerService;

class AcquirerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_getall()
    {
        $service = new AcquirerService();
        $acquirers = $service->getAll();

        $this->assertTrue($acquirers->count() >= 0);
    }

    public function test_has_attributes()
    {
        $service = new AcquirerService();
        $acquirers = $service->getAll();
        $acquirer = $acquirers->first();

        $this->assertTrue(count($acquirer->settings) > 0);
    }
}
