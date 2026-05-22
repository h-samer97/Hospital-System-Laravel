<?php

namespace App\Interfaces;

use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use App\Http\Requests\StoreGroupsRequest;

interface IGroups {

    public function index(): Response;
    public function store(StoreGroupsRequest $request): RedirectResponse;
    public function destroy(Group $group): RedirectResponse;

}



