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

    public function test_merchant_address()
    {
        $this->markTestSkipped('Relationships not in place');
    }

    public function test_update()
    {
        $account = new AccountService();
        $merchant = $account->update([
            'shop_name' => 'Demo Shop',
        ]);
        $this->assertEquals('Demo Shop', $merchant->shop_name);
    }
}
