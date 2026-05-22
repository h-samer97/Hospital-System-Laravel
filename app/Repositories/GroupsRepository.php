<?php

namespace App\Repositories;

use App\Interfaces\IGroups;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Group;
use App\Models\Service;
use App\Http\Requests\StoreGroupsRequest;
use Exception;

class GroupsRepository implements IGroups
{
    public function index() : Response {


        # find(n) => get one record
        # get() => get All Collection
        $groups = Group::with(['services'])
        ->select('id','name','notes','subtotal','discount','tax_percent','total','is_active','created_at')
        ->latest() # DASC
        ->get()
        ->map(fn(Group $group) => [
            'id' => $group->id,
            'name'        => $group->name,
            'notes'       => $group->notes,
            'subtotal'    => $group->subtotal,
            'discount'    => $group->discount,
            'tax_percent' => $group->tax_percent,
            'total'       => $group->total,
            'is_active'   => $group->is_active,
            'created_at'  => $group->created_at,
            'url_store'   => route('groups.store'),
            'services'    => $group->services->map(fn($service) => [
                    'id'         => $service->id,
                    'name'       => $service->name,
                    'quantity'   => $service->pivot->quantity,
                    'unit_price' => $service->pivot->unit_price,
            ]),
            'urls'      => [
                'destroy' => route('groups.destroy', $group->id),
            ]
        ]);

        $services = Service::where('is_active', true)
            ->select('id', 'name', 'price')
            ->get();

        return inertia::render('Groups/Index', [
            'groups' => $groups,
            'services' => $services,
            'url_store' => route('groups.store')
        ]);
    }


    public function store(StoreGroupsRequest $request) : RedirectResponse
    {
        try {
            
            $data = $request->validated();
            $items    = $data['items'];
            $discount = $data['discount'] ?? 0;
            $tax      = $data['tax_percent'] ?? 17;


            return redirect()->route('groups.index')->with('success', [
                ''
            ]);

        } catch(Exception $error) {
            return redirect()->route('groups.index')->with('error', $error->getMessage());
        }
    }

    public function destroy(Group $group) : RedirectResponse
    {
        try {
            $group->delete();
            return redirect()->route('groups.index')->with('success', 'Group deleted successfully');
        } catch(Exception $error) {
            return redirect()->route('groups.index')->with('error', $error->getMessage());
        }
    }

}