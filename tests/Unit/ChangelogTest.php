<?php

use QuickPay\Changelog\Changelog;

beforeEach(function () {
    $this->changelog = new Changelog();
});

it('returns a changelog', function () {
    assertObjectHasAttribute('changes', $this->changelog);
});
