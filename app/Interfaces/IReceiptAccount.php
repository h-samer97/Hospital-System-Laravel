<?php

namespace App\Interfaces;

use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Illuminate\Http\Request;

interface IReceiptAccount
{
  public function index(): Response;
  public function create(): Response;
  public function store(Request $request): RedirectResponse;
  public function edit(int $id): Response;
  public function update(Request $request, int $id): RedirectResponse;
  public function destroy(int $id): RedirectResponse;
}
