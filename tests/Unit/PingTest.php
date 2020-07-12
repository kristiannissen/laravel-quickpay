<?php

use QuickPay\Ping\Ping;

test('ping request returns pong', function () {
    assertEquals(
        'Pong from QuickPay API V10, scope is anonymous',
        Ping::send()->msg
    );
});
