<?php

namespace QuickPay\Tests\Unit;

use QuickPay\Tests\TestCase;
use QuickPay\Ping\Ping;

class PingTest extends TestCase
{
    protected $ping;
    public function setup(): void
    {
        parent::setup();
        $this->ping = new Ping();
    }
    public function test_ping_returns_model()
    {
        $this->assertEquals(
            'Pong from QuickPay API V10, scope is anonymous',
            $this->ping->get()->msg
        );
    }
}
