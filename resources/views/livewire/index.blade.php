<div class="main-content-label mg-b-5">
    <button class="btn btn-primary btn-with-icon btn-block-mobile" wire:click="show_form_add" type="button">
        <i class="fa fa-plus-circle"></i> إضافة مجموعة خدمات جديدة
    </button>
</div>

<br>

<div class="table-responsive">
    <table class="table table-hover mb-0 text-md-nowrap" id="example1" style="text-align: center">
        <thead>
            <tr class="table-secondary">
                <th class="border-bottom-0">#</th>
                <th class="border-bottom-0">الاسم</th>
                <th class="border-bottom-0">إجمالي العرض (شامل الضريبة)</th>
                <th class="border-bottom-0">الملاحظات</th>
                <th class="border-bottom-0">العمليات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($groups as $group)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="font-weight-bold text-primary">{{ $group->name }}</td>
                    <td>
                        <span class="badge badge-pill badge-success-light tx-14">
                            {{ number_format($group->total_with_tax, 2) }}
                        </span>
                    </td>
                    <td class="text-muted small">
                        {{ \Illuminate\Support\Str::limit($group->notes, 50) ?: 'لا توجد ملاحظات' }}
                    </td>
                    <td>
                        <div class="btn-icon-list">
                            <button wire:click="edit({{ $group->id }})" class="btn btn-primary btn-sm btn-icon" title="تعديل">
                                <i class="fa fa-edit"></i>
                            </button>
                            
                            {{-- إضافة تأكيد قبل الحذف لزيادة الأمان --}}
                            <button type="button" class="btn btn-danger btn-sm btn-icon" 
                                    wire:click="delete({{ $group->id }})" 
                                    wire:confirm="هل أنت متأكد من حذف هذه المجموعة؟"
                                    title="حذف">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-danger font-weight-bold">
                        لا توجد مجموعات خدمات مسجلة حالياً.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>