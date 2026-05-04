<div>
    {{-- رسائل النجاح --}}
    @if ($ServiceSaved)
        <div class="alert alert-success" id="success-alert">تم حفظ مجموعة الخدمات بنجاح.</div>
    @endif

    @if ($ServiceUpdated)
        <div class="alert alert-info" id="update-alert">تم تحديث البيانات بنجاح.</div>
    @endif

    @if($show_table)
        @include('livewire.index')
    @else
        <form wire:submit.prevent="saveGroup" autocomplete="off">
            @csrf
            {{-- بيانات المجموعة الأساسية --}}
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <label>اسم المجموعة</label>
                            <input wire:model="name_group" type="text" class="form-control @error('name_group') is-invalid @enderror">
                            @error('name_group') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <label>ملاحظات</label>
                            <textarea wire:model="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- جدول الخدمات الفرعية --}}
            <div class="card mt-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">الخدمات المتضمنة في المجموعة</h5>
                    <button type="button" class="btn btn-primary btn-sm" wire:click.prevent="addService">
                        <i class="fa fa-plus"></i> إضافة خدمة فرعية
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>اسم الخدمة</th>
                                    <th width="150">العدد</th>
                                    <th width="200">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($GroupsItems as $index => $groupItem)
                                    <tr wire:key="group-item-{{ $index }}">
                                        <td>
                                            @if($groupItem['is_saved'])
                                                {{ $groupItem['service_name'] }} 
                                                <span class="text-muted">({{ number_format($groupItem['service_price'], 2) }})</span>
                                            @else
                                                <select wire:model="GroupsItems.{{$index}}.service_id" 
                                                        class="form-control @error('GroupsItems.'.$index.'.service_id') is-invalid @enderror">
                                                    <option value="">-- اختر الخدمة --</option>
                                                    @foreach ($allServices as $service)
                                                        <option value="{{ $service->id }}">
                                                            {{ $service->name }} ({{ number_format($service->price, 2) }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('GroupsItems.'.$index.'.service_id') <small class="text-danger">{{ $message }}</small> @enderror
                                            @endif
                                        </td>
                                        <td>
                                            @if($groupItem['is_saved'])
                                                {{ $groupItem['quantity'] }}
                                            @else
                                                <input type="number" class="form-control" wire:model="GroupsItems.{{$index}}.quantity" min="1">
                                            @endif
                                        </td>
                                        <td>
                                            @if($groupItem['is_saved'])
                                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click.prevent="editService({{$index}})">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-success" wire:click.prevent="saveService({{$index}})">
                                                    <i class="fa fa-check"></i> تأكيد
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click.prevent="removeService({{$index}})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- قسم الحسابات المالية --}}
                    <div class="row mt-4">
                        <div class="col-lg-4 ms-auto">
                            <table class="table table-sm border-0">
                                <tr>
                                    <td class="font-weight-bold text-danger">الإجمالي الفرعي</td>
                                    <td class="text-left font-weight-bold">{{ number_format($this->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-danger">قيمة الخصم</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" wire:model.live="discount_value">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-danger">نسبة الضريبة (%)</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" wire:model.live="taxes">
                                    </td>
                                </tr>
                                <tr class="bg-light">
                                    <td class="font-weight-bold text-primary">الإجمالي النهائي</td>
                                    <td class="text-left font-weight-bold text-primary">{{ number_format($this->total, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <button class="btn btn-success px-5" type="submit">
                            <i class="fa fa-save"></i> حفظ كافة البيانات
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>