<?php

namespace QuickPay\Tests\Unit;

use QuickPay\Tests\TestCase;
use QuickPay\Account\AccountService;

class AccountTest extends TestCase
{
    protected $account;
    protected $merchant;

    public function setup(): void
    {
        parent::setup();
        $this->account = new AccountService();
        $this->merchant = $this->account->get();
    }

    public function test_get_returns_shop_name()
    {
        $this->assertEquals($this->merchant->shop_name, 'Demo Shop');
    }

    public function test_merchant_customer_address()
    {
        $this->assertEquals(
            $this->merchant->customer_address->name,
            'Demo Company'
        );
    }
}
