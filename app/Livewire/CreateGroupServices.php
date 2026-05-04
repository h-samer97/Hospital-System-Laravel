<?php

namespace App\Livewire;

use App\Models\Groups;
use App\Models\SingleServices;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateGroupServices extends Component
{
    // تعريف الخصائص (Properties)
    public $GroupsItems = [];

    public $name_group;

    public $notes;

    public $taxes = 0;

    public $discount_value = 0;

    public $group_id;

    // حالات التحكم في الواجهة
    public $show_table = true;

    public $updateMode = false;

    public $ServiceSaved = false;

    public $ServiceUpdated = false;

    /**
     * حساب الإجمالي قبل الخصم والضريبة
     * استخدام Computed Property يجعلها تعمل تلقائياً مع كل تغيير في المدخلات
     */
    #[Computed]
    public function subtotal()
    {
        return array_reduce($this->GroupsItems, function ($carry, $item) {
            if ($item['is_saved'] && isset($item['service_price'], $item['quantity'])) {
                return $carry + ($item['service_price'] * $item['quantity']);
            }

            return $carry;
        }, 0);
    }

    /**
     * حساب الإجمالي النهائي (بعد الخصم + الضريبة)
     */
    #[Computed]
    public function total()
    {
        $afterDiscount = $this->subtotal - (is_numeric($this->discount_value) ? $this->discount_value : 0);
        $taxAmount = $afterDiscount * ((is_numeric($this->taxes) ? $this->taxes : 0) / 100);

        return $afterDiscount + $taxAmount;
    }

    public function render()
    {
        return view('livewire.create-group-services', [
            'groups' => Groups::all(),
            'allServices' => SingleServices::all(), // جلب الخدمات للقائمة المنسدلة
        ]);
    }

    // --- عمليات إدارة الجدول الديناميكي ---

    public function addService()
    {
        $this->GroupsItems[] = [
            'service_id' => '',
            'quantity' => 1,
            'is_saved' => false,
            'service_name' => '',
            'service_price' => 0,
        ];
    }

    public function saveService($index)
    {
        $this->validate([
            "GroupsItems.$index.service_id" => 'required',
            "GroupsItems.$index.quantity" => 'required|numeric|min:1',
        ]);

        $service = SingleServices::findOrFail($this->GroupsItems[$index]['service_id']);
        $this->GroupsItems[$index]['service_name'] = $service->name;
        $this->GroupsItems[$index]['service_price'] = $service->price;
        $this->GroupsItems[$index]['is_saved'] = true;
    }

    public function editService($index)
    {
        $this->GroupsItems[$index]['is_saved'] = false;
    }

    public function removeService($index)
    {
        unset($this->GroupsItems[$index]);
        $this->GroupsItems = array_values($this->GroupsItems); // إعادة ترتيب المفاتيح
    }

    // --- عمليات حفظ المجموعة ---

    public function saveGroup()
    {
        $wasUpdate = $this->updateMode;

        // التحقق من البيانات
        $this->validate([
            'name_group' => 'required|string',
            'taxes' => 'required|numeric',
            'discount_value' => 'required|numeric',
            'GroupsItems' => 'required|array|min:1',
            'GroupsItems.*.service_id' => 'required|exists:single_services,id',
            'GroupsItems.*.quantity' => 'required|numeric|min:1',
        ]);

        // تحديد ما إذا كان تحديث أو إنشاء جديد
        $group = $wasUpdate ? Groups::findOrFail($this->group_id) : new Groups;

        // تعبئة البيانات (Logic Unified)
        $group->name = $this->name_group;
        $group->notes = $this->notes;
        $group->total_before_discount = $this->subtotal;
        $group->discount_value = $this->discount_value;
        $group->total_after_discount = $this->subtotal - $this->discount_value;
        $group->tax_rate = $this->taxes;
        $group->total_with_tax = $this->total;
        $group->save();

        // حفظ العلاقات (Sync تغنيك عن detach و attach يدوياً)
        $pivotData = collect($this->GroupsItems)
            ->mapWithKeys(function ($item) {
                return [$item['service_id'] => ['quantity' => $item['quantity']]];
            })
            ->toArray();

        $group->service_group()->sync($pivotData);

        $this->show_table = true;
        $this->updateMode = false;
        $this->reset(['GroupsItems', 'name_group', 'notes', 'discount_value', 'taxes']);

        if ($wasUpdate) {
            $this->ServiceUpdated = true;
        } else {
            $this->ServiceSaved = true;
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $this->show_table = false;
        $this->group_id = $id;
        $this->ServiceSaved = false;
        $this->ServiceUpdated = false;

        $group = Groups::with('service_group')->findOrFail($id);
        $this->name_group = $group->name;
        $this->notes = $group->notes;
        $this->discount_value = $group->discount_value;
        $this->taxes = $group->tax_rate;

        $this->GroupsItems = [];
        foreach ($group->service_group as $service) {
            $this->GroupsItems[] = [
                'service_id' => $service->id,
                'quantity' => $service->pivot->quantity ?? 1,
                'is_saved' => true,
                'service_name' => $service->name,
                'service_price' => $service->price,
            ];
        }
    }

    public function delete($id)
    {
        Groups::destroy($id);

        return redirect()->to('/Add_GroupServices');
    }

    public function show_form_add()
    {
        $this->reset(['GroupsItems', 'name_group', 'notes', 'updateMode', 'ServiceSaved', 'ServiceUpdated']);
        $this->show_table = false;
    }
}
