<?php

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

uses(TestCase::class);

it('registers single services resource routes', function () {
    expect(Route::has('SingleService.index'))->toBeTrue();
    expect(Route::has('SingleService.create'))->toBeTrue();
    expect(Route::has('SingleService.store'))->toBeTrue();
    expect(Route::has('SingleService.edit'))->toBeTrue();
    expect(Route::has('SingleService.update'))->toBeTrue();
    expect(Route::has('SingleService.destroy'))->toBeTrue();

    $route = Route::getRoutes()->getByName('SingleService.index');

    expect($route)->not->toBeNull();
    expect($route->getActionName())->toBe('App\\Http\\Controllers\\SingleServicesController@index');
});
