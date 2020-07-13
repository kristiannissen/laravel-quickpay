<?php

use QuickPay\Ping\Ping;

beforeEach(function () {
    $this->ping = Ping::send();
});

test('ping returns pong json', function () {
    assertJsonStringEqualsJsonString(
        json_encode([
            'msg' => 'Pong from QuickPay API V10, scope is anonymous',
            'scope' => 'anonymous',
            'version' => 'v10',
        ]),
        $this->ping->toJson()
    );
})->skip();
