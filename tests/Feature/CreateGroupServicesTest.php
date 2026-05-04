<?php

use App\Livewire\CreateGroupServices;
use App\Models\Groups;
use App\Models\SingleServices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['database.default' => 'mysql']);
});

test('create group service with nested service items', function () {
    $service = SingleServices::create([
        'name' => 'Blood Test',
        'price' => 100.00,
    ]);

    Livewire::test(CreateGroupServices::class)
        ->call('addService')
        ->set('GroupsItems.0.service_id', $service->id)
        ->set('GroupsItems.0.quantity', 2)
        ->call('saveService', 0)
        ->set('name_group', 'Test Group')
        ->set('notes', 'Test notes')
        ->set('taxes', 5)
        ->set('discount_value', 10)
        ->call('saveGroup')
        ->assertSet('ServiceSaved', true)
        ->assertSet('show_table', true);

    $this->assertDatabaseHas('groups', [
        'name' => 'Test Group',
        'discount_value' => 10,
    ]);

    $group = Groups::first();

    expect($group)->not->toBeNull();
    expect($group->service_group)->toHaveCount(1);
    expect($group->service_group->first()->pivot->quantity)->toBe(2);
});
