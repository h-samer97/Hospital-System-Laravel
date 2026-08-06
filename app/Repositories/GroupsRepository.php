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
    public function index(): Response
    {
        $groups = Group::with('services')
            ->select('id', 'name', 'notes', 'subtotal', 'discount', 'tax_percent', 'total', 'is_active', 'created_at')
            ->latest()
            ->get()
            ->map(fn(Group $g) => [
                'id'          => $g->id,
                'name'        => $g->name,
                'notes'       => $g->notes,
                'subtotal'    => $g->subtotal,
                'discount'    => $g->discount,
                'tax_percent' => $g->tax_percent,
                'total'       => $g->total,
                'is_active'   => $g->is_active,
                'created_at'  => $g->created_at,
                // الخدمات المرتبطة بالمجموعة مع بيانات الـ pivot
                'services'    => $g->services->map(fn($s) => [
                    'id'         => $s->id,
                    'name'       => $s->name,
                    'quantity'   => $s->pivot->quantity,
                    'unit_price' => $s->pivot->unit_price,
                ]),
                'delete_url'  => route('groups.destroy', $g->id),
            ]);

        // كل الخدمات المتاحة للاختيار في الفورم
        $services = Service::where('is_active', true)
            ->select('id', 'name', 'price')
            ->get();

        return Inertia::render('Groups/Index', [
            'groups'    => $groups,
            'services'  => $services,
            'store_url' => route('groups.store'),
        ]);
    }

    // ============================================================
    // store — حفظ المجموعة مع حساب الإجماليات
    // ============================================================
    public function store(StoreGroupsRequest $request): RedirectResponse
    {
        $data     = $request->validated();
        $items    = $data['items'];
        $discount = $data['discount'] ?? 0;
        $tax      = $data['tax_percent'] ?? 17;

        // حساب الـ subtotal من الـ items
        $subtotal = collect($items)->sum(function ($item) {
            $service = Service::find($item['service_id'], 'service_id');
            return $service->price * $item['quantity'];
        });

        $afterDiscount = $subtotal - $discount;
        $total         = $afterDiscount * (1 + $tax / 100);

        // إنشاء المجموعة
        $group = Group::create([
            'name'        => $data['name'],
            'notes'       => $data['notes'] ?? null,
            'subtotal'    => $subtotal,
            'discount'    => $discount,
            'tax_percent' => $tax,
            'total'       => $total,
        ]);

        // ربط الخدمات بالمجموعة عبر الـ pivot
        $syncData = [];
        foreach ($items as $item) {
            $service = Service::find($item['service_id'], 'service_id');
            // نخزن unit_price وقت الحفظ لأن السعر قد يتغير
            $syncData[$item['service_id']] = [
                'quantity'   => $item['quantity'],
                'unit_price' => $service->price,
            ];
        }

        $group->services()->sync($syncData);

        return redirect()->route('groups.index')->with('flash', [
            'type'    => 'success',
            'message' => 'Group created successfully',
        ]);
    }

    // ============================================================
    // destroy
    // ============================================================
    public function destroy(Group $group): RedirectResponse
    {
        // detach قبل الحذف لتنظيف الـ pivot
        $group->services()->detach();
        $group->delete();

        return redirect()->route('groups.index')->with('flash', [
            'type'    => 'success',
            'message' => 'Group deleted successfully',
        ]);
    }
}
