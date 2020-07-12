<?php

use QuickPay\Changelog\Changelog;

beforeEach(function() {
    $this->changelog = Changelog::get();
});

it('returns a changelog', function () {
    assertArrayHasKey('changes', $this->changelog);
});
