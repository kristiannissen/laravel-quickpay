<?php

namespace QuickPay\Repository;

use QuickPay\QuickPayModel;

interface AccountRepositoryInterface {
	public function get() : QuickPayModel;
}