<?php


namespace App\Repositories;

use App\Http\Requests\StoreAmbulanceRequest;
use App\Http\Requests\UpdateAmbulanceRequest;
use App\Interfaces\IAmbulance;
use App\Models\Ambulances;
use Exception;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Inertia\Inertia;
use Override;

class AmbulancesRepository implements IAmbulance
{
  #[Override]
  public function index(): Response
  {
    $ambulances = Ambulances::query()
      ->select(
        'id',
        'car_number',
        'car_model',
        'car_year_made',
        'driver_name',
        'driver_license_number',
        'is_available',
        'status',
        'notes',
        'created_at',
        'car_type',
        'driver_phone'
      )
      ->latest()
      ->get()
      ->map(fn(Ambulances $ambulance) => [
        'id'            => $ambulance->id,
        'car_number'    => $ambulance->car_number,
        'car_model'     => $ambulance->car_model,
        'car_year_made' => $ambulance->car_year_made,
        'driver_name'   => $ambulance->driver_name,
        'driver_license_number' => $ambulance->driver_license_number,
        'is_available'  => $ambulance->is_available,
        'notes'         => $ambulance->notes,
        'status'        => $ambulance->status,
        'car_type'      => $ambulance->car_type,
        'driver_phone'  => $ambulance->driver_phone,
        'created_at'    => $ambulance->created_at,
        'urls' => [
          'update' => route('ambulances.update',  $ambulance->id),
          'destroy' => route('ambulances.destroy', $ambulance->id),
          ],
          ]);
          
          return Inertia::render('Ambulance/Index', [
            'ambulances' => $ambulances,
            'store' => route('ambulances.store'),
    ]);
  }

  #[Override]
  public function store(StoreAmbulanceRequest $request): RedirectResponse
  {
    Ambulances::create($request->validated());

        return redirect()
            ->route('ambulances.index')
            ->with('flash', [
                'type'    => 'success',
                'message' => 'Ambulance added successfully',
            ]);
  }

  #[Override]
  public function update(UpdateAmbulanceRequest $request, Ambulances $ambulances): RedirectResponse
  {
   $ambulances->update($request->validated());

        return redirect()
            ->route('ambulances.index')
            ->with('flash', [
                'type'    => 'success',
                'message' => 'Ambulance updated successfully',
            ]);
  }

  #[Override]
  public function edit(Ambulances $ambulances): Response
  {
    return Inertia::render('ambulances.edit', [
      'ambulance' => [
        'id' => $ambulances->id,
        'car_number' => $ambulances->car_number,
        'car_model' => $ambulances->car_model,
        'car_year_made' => $ambulances->car_year_made,
        'driver_name' => $ambulances->driver_name,
        'driver_license_number' => $ambulances->driver_license_number,
        'is_available' => $ambulances->is_available,
        'status' => $ambulances->status,
        'notes' => $ambulances->notes,
      ],
      'urls' => [
        'update' => route('ambulances.update',  $ambulances->id),
        'destroy' => route('ambulances.destroy', $ambulances->id),
        'url_store' => route('ambulances.store'),
      ],
    ]);
  }

  #[Override]
  public function destroy(Ambulances $ambulances): RedirectResponse
  {
    try {
        Ambulances::destroy($ambulances->id);

        return redirect()
            ->route('ambulances.index')
            ->with('flash', [
                'type'    => 'success',
                'message' => 'Ambulance deleted successfully',
            ]);

        } catch (\Exception $e) {
            return redirect()
                ->route('ambulances.index')
                ->with('flash', [
                    'type'    => 'error',
                    'message' => 'Cannot delete: ambulance may be in use: ' . $e->getMessage(),
                ]);
        }
  }
}
