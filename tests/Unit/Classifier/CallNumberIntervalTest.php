<?php

use Bclib\GetBooksFromAlma\Classifier\CallNumber;
use Bclib\GetBooksFromAlma\Classifier\CallNumberInterval;

test('lessThan() returns true for intervals with same low bound but lower high bound', function () {
    $callno_1 = new CallNumber('AB 500');
    $callno_2 = new CallNumber('BC 100');
    $higher_interval = new CallNumberInterval($callno_1, $callno_2);

    $callno_3 = new CallNumber('AB 500');
    $callno_4 = new CallNumber('AB 600');
    $lower_interval = new CallNumberInterval($callno_3, $callno_4);

    expect($lower_interval->lessThan($higher_interval))->toBeTrue();
});

test('lessThan() returns true for intervals with same high bound but lower low bound', function () {
    $callno_1 = new CallNumber('AB 500');
    $callno_2 = new CallNumber('BC 100');
    $higher_interval = new CallNumberInterval($callno_1, $callno_2);

    $callno_3 = new CallNumber('AB 100');
    $callno_4 = new CallNumber('AB 600');
    $lower_interval = new CallNumberInterval($callno_3, $callno_4);

    expect($lower_interval->lessThan($higher_interval))->toBeTrue();
});

test('lessThan() returns false for intervals that are identical', function () {
    $callno_1 = new CallNumber('AB 500');
    $callno_2 = new CallNumber('BC 100');
    $interval_1 = new CallNumberInterval($callno_1, $callno_2);
    $interval_2 = new CallNumberInterval($callno_1, $callno_2);

    expect($interval_2->lessThan($interval_1))->toBeFalse();
});

test('equalTo() returns true for equal intervals', function() {
    $callno_1 = new CallNumber('AB 500');
    $callno_2 = new CallNumber('BC 100');
    $interval_1 = new CallNumberInterval($callno_1, $callno_2);

    $callno_3 = new CallNumber('AB 500');
    $callno_4 = new CallNumber('BC 100');
    $interval_2 = new CallNumberInterval($callno_3, $callno_4);

    expect($interval_1->equalTo($interval_2))->toBeTrue();
});

test('equalTo() returns false for unequal intervals', function() {
    $callno_1 = new CallNumber('AB 500');
    $callno_2 = new CallNumber('BC 100');
    $interval_1 = new CallNumberInterval($callno_1, $callno_2);

    $callno_3 = new CallNumber('AB 200');
    $callno_4 = new CallNumber('BC 300');
    $interval_2 = new CallNumberInterval($callno_3, $callno_4);

    expect($interval_1->equalTo($interval_2))->toBeFalse();
});

test('intersect() returns true when interval is wholly contained in other interval', function(){
    $callno_1 = new CallNumber('AB 500');
    $callno_2 = new CallNumber('BC 100');
    $surrounding_interval = new CallNumberInterval($callno_1, $callno_2);

    $callno_3 = new CallNumber('AB 600');
    $callno_4 = new CallNumber('AB 600');
    $contained_interval = new CallNumberInterval($callno_3, $callno_4);

    expect($surrounding_interval->intersect($contained_interval))->toBeTrue();
});

test('intersect() returns true when intervals partially overlap', function() {
    $callno_1 = new CallNumber('DE 500');
    $callno_2 = new CallNumber('FG 100');
    $lower_interval = new CallNumberInterval($callno_1, $callno_2);

    $callno_3 = new CallNumber('FG 001');
    $callno_4 = new CallNumber('HI 100');
    $higher_interval = new CallNumberInterval($callno_3, $callno_4);

    $this->assertTrue($lower_interval->intersect($higher_interval));

});

test('intersect() returns false when intervals do not intersect at all', function(){
    $callno_1 = new CallNumber('AB 500');
    $callno_2 = new CallNumber('BC 100');
    $interval_1 = new CallNumberInterval($callno_1, $callno_2);

    $callno_3 = new CallNumber('DE 500');
    $callno_4 = new CallNumber('FG 100');
    $interval_2 = new CallNumberInterval($callno_3, $callno_4);

    expect($interval_1->intersect($interval_2))->toBeFalse();
});

test('::fromArray() builds interval correctly', function () {
    $callno_lo = new CallNumber('AB 500');
    $callno_hi = new CallNumber('BC 100');

    $interval_from_array = CallNumberInterval::fromArray([$callno_lo, $callno_hi]);
    $interval_from_constructor = new CallNumberInterval($callno_lo, $callno_hi);

    expect($interval_from_array)->toEqual($interval_from_constructor);
});

test('getLow() returns the lower interval value', function() {
    $callno_lo = new CallNumber('AB 500');
    $callno_hi = new CallNumber('BC 100');
    $interval = new CallNumberInterval($callno_lo, $callno_hi);

    expect($interval->getLow())->toBe($callno_lo);
});

test('getHigh() returns the higher interval value', function() {
    $callno_lo = new CallNumber('AB 500');
    $callno_hi = new CallNumber('BC 100');
    $interval = new CallNumberInterval($callno_lo, $callno_hi);

    expect($interval->getHigh())->toBe($callno_hi);
});