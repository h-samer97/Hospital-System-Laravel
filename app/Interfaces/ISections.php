<?php

namespace App\Interfaces;

use App\Http\Requests\dashboard\StoreSectionRequest;
use App\Http\Requests\dashboard\UpdateSectionRequest;
use App\Models\Section;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

interface ISections
{
    public function index(): Response;

    public function store(StoreSectionRequest $request): RedirectResponse;

    public function update(UpdateSectionRequest $request, Section $section): RedirectResponse;

    public function destroy(Section $section): RedirectResponse;
}