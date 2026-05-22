<?php

namespace App\Http\Controllers;

use App\Interfaces\IGroups;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use App\Http\Requests\StoreGroupsRequest;

class GroupController extends Controller
{
    public function __construct(
        private readonly IGroups $groups
    ) {}

    public function index(): Response
    {
        return $this->groups->index();
    }

    public function store(StoreGroupRequest $request): RedirectResponse
    {
        return $this->groups->store($request);
    }

    public function destroy(Group $group): RedirectResponse
    {
        return $this->groups->destroy($group);
    }
}