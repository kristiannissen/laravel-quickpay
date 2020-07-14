<?php

use QuickPay\Repository\AccountRepository;

it('should return an account', function () {
    $repo = new AccountRepository();
    $account = $repo->get();
    dd($account);
})->skip();
