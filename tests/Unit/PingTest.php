<?php

use QuickPay\Ping\Ping;

test('ping returns pong object', function () {
    $ping = new Ping();
    $pong = $ping->get();

    assertEquals('Pong from QuickPay API V10, scope is anonymous', $pong->msg);
});
