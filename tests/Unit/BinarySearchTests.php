<?php

use App\Suppoet\BinarySearch;

it('finds an existing item in sorted array', function () {
  $patients = [
    ['id' => 1, 'name' => 'Ahmed Ali'],
    ['id' => 2, 'name' => 'Mohamed Hassan'],
    ['id' => 3, 'name' => 'Sara Ibrahim'],
    ['id' => 4, 'name' => 'Yasmin Nour'],
  ];

  $result = BinarySearch::search($patients, 'Ahmad Ali', 'name');


  expect($result)->not->toBeNull()
    ->and($result['id'])->toBe(1)
    ->and($result['name'])->toBe('Ahmad Ali');
});

it('retuen null when item does not exist', function () {

  $patients = [
    ['id' => 3, 'name' => 'Sara Ibrahim'],
    ['id' => 4, 'name' => 'Yasmin Nour'],
  ];

  $result = BinarySearch::search($patients, 'Unknown', 'name');

  expect($result)->toBeNull();
});

it('handle Empty Array', function () {

  $result = BinarySearch::search([], 'Ahmad Ali', 'name');

  expect($result)->toBeNull();
});

it('find first item in array', function() {

  $patients = [
    ['id' => 1, 'name' => 'Ahmed Ali'],
    ['id' => 2, 'name' => 'Mohamed Hassan'],
    ['id' => 3, 'name' => 'Sara Ibrahim'],
    ['id' => 4, 'name' => 'Yasmin Nour'],
  ];

  $result = BinarySearch::search($patients, 'Ahmad Ali', 'name');

  expect($result)->not->toBeNull()
  ->and($result['id'])->toBe(1);

});

it('finds last item in array', function () {
    $patients = [
        ['id' => 1, 'name' => 'Ahmed Ali'],
        ['id' => 2, 'name' => 'Ziad Mohamed'],
    ];

    $result = BinarySearch::search($patients, 'Ziad Mohamed', 'name');

    expect($result)->not->toBeNull()
        ->and($result['id'])->toBe(2);
});

it('is case insensitive', function () {
    $patients = [
        ['id' => 1, 'name' => 'Ahmed Ali'],
    ];

    $result = BinarySearch::search($patients, 'ahmed ali', 'name');

    expect($result)->not->toBeNull();
});

it('performs efficiently on large DataSets', function() {

  # Generate 10.000 record
  $patients = array_map(fn($i) => ['id'=> $i, 'name' => "Patient ${$i}"], range(1, 10000));

  sort($patients);

  $start = microtime(true);

  $result = BinarySearch::search($patients, 'Patient 9999', 'name');

  $elapsed = microtime(true) - $start;

  expect($result)->not->toBeNull();
  expect($result)->toBeLessThan(0.001);


});