<?php

namespace App\Interfaces;


interface IGroups {

    public function index(): Response;
    public function store(StoreGroupRequest $request): RedirectResponse;
    public function destroy(Group $group): RedirectResponse;

}



