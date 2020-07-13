<?php
/**
 * This is to test that the abstract model works
 * when inherited. The abstract Model is not tested, instead
 * the concrete class is tested
 */
use QuickPay\Ping\Pong;

beforeEach(function () {});

it('should return Hello Kitty', function () {
    $pong = new Pong(['msg' => 'Hello Kitty']);
    assertEquals('Hello Kitty', $pong->msg);
});

it('should return Hello Pussy', function () {
    $pong = new Pong();
    $pong->msg = 'Hello Pussy';
    assertEquals('Hello Pussy', $pong->msg);
});

it('should return JSON', function () {
    $pong = new Pong(['msg' => 'Hello Kitty']);
    assertJsonStringEqualsJsonString(
        json_encode([
            'msg' => 'Hello Kitty',
        ]),
        $pong->toJson()
    );
});
