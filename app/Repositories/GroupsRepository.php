<?php

namespace App\Repositories;

use App\Interfaces\IGroups;
use Inertia\Inertia;
use Inertia\Response;

class GroupsRepository implements IGroups
{
    public function index() : Response {


        # find(n) => get one record
        # get() => get All Collection
        $groups = Groups::with(['services'])
        ->select('id','name','notes','subtotal','discount','tax_percent','total','is_active','created_at')
        ->latest() # DASC
        ->get()
        ->map(fn(Groups $group) => [
            'id' => $group->id,
            'name'        => $group->name,
            'notes'       => $group->notes,
            'subtotal'    => $group->subtotal,
            'discount'    => $group->discount,
            'tax_percent' => $group->tax_percent,
            'total'       => $group->total,
            'is_active'   => $group->is_active,
            'created_at'  => $group->created_at,
            'services'    => $group->services->map(fn($service) => [
                    'id'         => $service->id,
                    'name'       => $service->name,
                    'quantity'   => $service->pivot->quantity,
                    'unit_price' => $service->pivot->unit_price,
            ]),
            'url_delete' => route('groups.destroy', $group->id),
            'url_update' => route('groups.update', $group->id),
            'url_store' => route('groups.store'),
        ]);

        $services = Service::where('is_active', true)
            ->select('id', 'name', 'price')
            ->get();

        return inertia::render('Pages/Groups/Index', [
            'groups' => $groups,
            'services' => $services
        ]);
    }


    public function store(StoreGroupsRequest $request)
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

}