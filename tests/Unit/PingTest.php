<?php

use QuickPay\Repository\PingRepository;

test('ping returns pong object', function () {
    $ping = new PingRepository();
    $pong = $ping->get();

    assertEquals('Pong from QuickPay API V10, scope is anonymous', $pong->msg);
});
