<?php

use App\Models\Groups;
use Tests\TestCase;

uses(TestCase::class);

it('creates a valid groups model through the factory', function () {
    $group = Groups::factory()->make();

    expect($group->total_before_discount)->toBeFloat();
    expect($group->discount_value)->toBeFloat();
    expect($group->total_after_discount)->toBeFloat();
    expect($group->tax_rate)->toBeString();
    expect($group->total_with_tax)->toBeFloat();
});
