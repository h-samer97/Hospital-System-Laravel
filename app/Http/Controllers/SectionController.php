<?php

namespace App\Http\Controllers;

use App\Interfaces\ISections;
use App\Repositories\SectionsRepositories;
use App\Http\Requests\dashboard\StoreSectionRequest;
use App\Http\Requests\dashboard\UpdateSectionRequest;
use App\Models\Section;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class SectionController extends Controller implements ISections
{
    protected $sectionsRepository;

    public function __construct(SectionsRepositories $sectionsRepository)
    {
        $this->sectionsRepository = $sectionsRepository;
    }

    public function index(): Response
    {
        return $this->sectionsRepository->index();
    }

    public function store(StoreSectionRequest $request): RedirectResponse
    {
        return $this->sectionsRepository->store($request);
    }

    public function update(UpdateSectionRequest $request, Section $section): RedirectResponse
    {
        return $this->sectionsRepository->update($request, $section);
    }

    public function destroy(Section $section): RedirectResponse
    {
        return $this->sectionsRepository->destroy($section);
    }
}
