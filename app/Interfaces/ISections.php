<?php

namespace App\Interfaces;

use App\Http\Requests\Dashboard\StoreSectionRequest;
use App\Http\Requests\Dashboard\UpdateSectionRequest;
use App\Models\Section;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

interface ISections
{
    public function index(): Response;

    public function store(StoreSectionRequest $request): RedirectResponse;

    public function update(UpdateSectionRequest $request, Section $section): RedirectResponse;

    public function destroy(Section $section): RedirectResponse;

    public function show(Section $section): RedirectResponse;
}
