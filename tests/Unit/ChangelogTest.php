<?php

use QuickPay\Changelog\Changelog;

it('should return an array', function () {
    $changelog = new Changelog();
    $changes = $changelog->get();
    assertIsObject($changes->changes);
});
