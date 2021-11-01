<?php

test('getSubject() returns subject', function () {
    $topic = new \Bclib\GetBooksFromAlma\Classifier\Topic('Otters', 'Diet', 'Shellfish');
    expect($topic->getSubject())->toBe('Otters');
});

test('getTopic() returns topic', function () {
    $topic = new \Bclib\GetBooksFromAlma\Classifier\Topic('Otters', 'Diet', 'Shellfish');
    expect($topic->getTopic())->toBe('Diet');
});

test('getSubtopic() returns subtopic', function () {
    $topic = new \Bclib\GetBooksFromAlma\Classifier\Topic('Otters', 'Diet', 'Shellfish');
    expect($topic->getSubtopic())->toBe('Shellfish');
});

test('Evaluating as string with _toString() and no subtopic returns correctly formatted string', function () {
    $topic = new \Bclib\GetBooksFromAlma\Classifier\Topic('Otters', 'Diet');
    expect("{$topic}")->toBe('Otters:Diet');
});

test('Evaluating as string with _toString() with subtopic returns correctly formatted string', function () {
    $topic = new \Bclib\GetBooksFromAlma\Classifier\Topic('Otters', 'Diet', 'Shellfish');
    expect("{$topic}")->toBe('Otters:Diet:Shellfish');
});