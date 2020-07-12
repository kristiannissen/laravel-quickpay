<?php

use \Mockery as m;

beforeEach(function () {});

afterEach(function () {
    m::close();
});

test('assert true is true', function () {
    assertTrue(true);
});
