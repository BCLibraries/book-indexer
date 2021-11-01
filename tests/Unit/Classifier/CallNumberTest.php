<?php

use Bclib\GetBooksFromAlma\Classifier\CallNumber;

test('lessThan() returns true when call number is lower', function () {
    $call_no_one = new CallNumber('ml 309 b49');
    $call_no_two = new CallNumber('n 7255 b44');

    expect($call_no_one->isLessThan($call_no_two))->toBeTrue();
});

test('lessThan() returns false when call humber is higher', function () {
    $call_no_one = new CallNumber('ml 309 b49');
    $call_no_two = new CallNumber('n 7255 b44');

    expect($call_no_two->isLessThan($call_no_one))->toBeFalse();
});

test('lessThan() returns false when call humber is the same', function () {
    $call_no_one = new CallNumber('ml 309 b49');
    $call_no_two= new CallNumber('ml309 b49');

    expect($call_no_one->isLessThan($call_no_two))->toBeFalse();
});

test('isEqualTo() returns true when call numbers are equal', function() {
    $call_no_one = new CallNumber('ml 309 b49');
    $call_no_two = new CallNumber('ml309 b49');

    expect($call_no_one->isEqualTo($call_no_two))->toBeTrue();
});


test('isEqualTo() returns true when call number is the same', function() {
    $call_no_one = new CallNumber('ml 309 b49');

    expect($call_no_one->isEqualTo($call_no_one))->toBeTrue();
});

test('isEqualTo() returns false when call numbers are not equal', function() {
    $call_no_one = new CallNumber('ml 309 b49');
    $call_no_two = new CallNumber('n 7255 b44');

    expect($call_no_one->isEqualTo($call_no_two))->toBeFalse();
});