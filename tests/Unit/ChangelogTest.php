<?php

namespace QuickPay\Tests\Unit;

use QuickPay\Tests\TestCase;
use QuickPay\Changelog\Changelog;

class ChangelogTest extends TestCase
{
    protected $changelog;
    public function setup(): void
    {
        parent::setup();
        $this->changelog = new Changelog();
    }
    public function test_changelog_returns_model()
    {
        $this->assertEquals('object', gettype($this->changelog->get()));
    }
}
