<?php

namespace QuickPay\Tests\Unit;

use QuickPay\Tests\TestCase;
use QuickPay\Account\AccountService;

class AccountTest extends TestCase
{
    protected $account;
    public function setup(): void
    {
        parent::setup();
        $this->account = new AccountService();
    }

    public function test_get_returns_shop_name()
    {
        $this->assertEquals($this->account->get()->shop_name, 'Demo Shop');
    }

    public function test_merchant_customer_address()
    {
        $this->assertEquals(
            $this->account->get()->customerAdress->name,
            'Demo Shop'
        );
    }
}
