<?php

namespace QuickPay\Changelog\Contracts;

use QuickPay\QuickPayModel;

interface ChangelogInterface {
    public function get() : ?QuickPayModel;
}
