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
        $this->assertEquals($this->merchant->address->name, 'Demo Company');
    }

    public function test_patch_updates_merchant()
    {
        $merchant = $this->merchant;
        $account = new AccountService();
        $merchant = $account->patch($merchant);
        $this->assertEquals(
            'Demo Shop',
            $merchant->shop_name
        );
    }
}
