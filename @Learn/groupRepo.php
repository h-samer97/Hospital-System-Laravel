<?php

namespace App\Repository\Groups;

use App\Http\Requests\Dashboard\StoreGroupRequest;
use App\Interfaces\Groups\GroupRepositoryInterface;
use App\Models\Group;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class GroupRepository
 * 
 * هذا الـ Repository مسؤول عن كل العمليات المتعلقة بـ Group (مجموعات الخدمات)
 * 
 * لماذا نستخدم Repository Pattern؟
 * - فصل منطق التعامل مع قاعدة البيانات عن الـ Controllers
 * - تسهيل الصيانة والتعديل مستقبلاً
 * - تسهيل كتابة Unit Tests
 */
class GroupRepository implements GroupRepositoryInterface
{
    /**
     * ============================================================
     * index() - عرض جميع المجموعات مع خدماتها
     * ============================================================
     * 
     * ما وظيفة هذه الدالة؟
     * - جلب جميع المجموعات من قاعدة البيانات
     * - جلب الخدمات المرتبطة بكل مجموعة (مع بيانات pivot)
     * - جلب جميع الخدمات المتاحة لعرضها في نموذج الإضافة
     * - إرجاع البيانات إلى صفحة React (Inertia)
     * 
     * @return Response - كائن Inertia مسؤول عن رندر صفحة React مع البيانات
     */
    public function index(): Response
    {
        /**
         * الخطوة 1: جلب المجموعات من قاعدة البيانات
         * 
         * Group::with('services')
         * - with('services'): تحميل مسبق (Eager Loading) لعلاقة services
         *   لمنع مشكلة N+1 (استعلام لكل مجموعة)
         * 
         * ->select('id','name',...)
         * - تحديد الأعمدة التي نريدها فقط (زيادة الأداء وتقليل نقل البيانات)
         * - لا نجلب كل الأعمدة (avoid SELECT *)
         * 
         * ->latest()
         * - ترتيب تنازلي حسب created_at (الأحدث أولاً)
         * 
         * ->get()
         * - تنفيذ الاستعلام وجلب النتائج كـ Collection
         * 
         * ->map(fn(Group $g) => [...])
         * - تحويل كل مجموعة إلى مصفوفة مخصصة (formatting)
         * - نستخدم map بدلاً من return المجموعة مباشرة لأننا نريد:
         *   1. تحديد الأعمدة التي نرسلها للـ Frontend
         *   2. تنسيق بيانات pivot للخدمات
         *   3. إضافة delete_url (حتى نستخدمها في React)
         */
        $groups = Group::with('services')  // تحميل الخدمات المرتبطة
            ->select('id', 'name', 'notes', 'subtotal', 'discount', 'tax_percent', 'total', 'is_active', 'created_at')
            ->latest()  // الأحدث أولاً
            ->get()
            ->map(fn(Group $g) => [
                // البيانات الأساسية للمجموعة
                'id'          => $g->id,
                'name'        => $g->name,
                'notes'       => $g->notes,
                'subtotal'    => $g->subtotal,
                'discount'    => $g->discount,
                'tax_percent' => $g->tax_percent,
                'total'       => $g->total,
                'is_active'   => $g->is_active,
                'created_at'  => $g->created_at,
                
                /**
                 * معالجة الخدمات المرتبطة
                 * 
                 * $g->services -> مجموعـة الـ Collection من الخدمات المرتبطة بهذه المجموعة
                 * ->map(fn($s) => [...]) -> تحويل كل خدمة إلى مصفوفة مخصصة
                 * 
                 * $s->pivot->quantity -> الوصول إلى عمود quantity في جدول pivot (service_group)
                 * $s->pivot->unit_price -> الوصول إلى سعر الخدمة وقت إضافتها للمجموعة
                 * 
                 * لماذا نخزن unit_price في pivot؟
                 * - لأن سعر الخدمة في جدول services قد يتغير مستقبلاً
                 * - نريد حفظ السعر الذي تمت به الصفقة وقت الشراء
                 */
                'services'    => $g->services->map(fn($s) => [
                    'id'         => $s->id,
                    'name'       => $s->name,
                    'quantity'   => $s->pivot->quantity,      // الكمية من pivot
                    'unit_price' => $s->pivot->unit_price,    // السعر عند الإضافة من pivot
                ]),
                
                /**
                 * إنشاء رابط الحذف
                 * route('Groups.destroy', $g->id)
                 * - ينتج رابط مثل: /groups/5
                 * - نمرره للـ Frontend بدلاً من إنشائه في JavaScript
                 */
                'delete_url'  => route('Groups.destroy', $g->id),
            ]);

        /**
         * الخطوة 2: جلب جميع الخدمات المتاحة
         * 
         * Service::where('is_active', true)
         * - نأخذ فقط الخدمات المفعلة (is_active = true)
         * 
         * ->select('id', 'name', 'price')
         * - نحدد الأعمدة التي نحتاجها فقط
         * 
         * ->get()
         * - جلب النتائج كـ Collection
         */
        $services = Service::where('is_active', true)
            ->select('id', 'name', 'price')
            ->get();

        /**
         * الخطوة 3: إرجاع البيانات إلى صفحة React
         * 
         * Inertia::render('Dashboard/Groups/Index', [...])
         * 
         * كيف يعمل Inertia؟
         * - يمرر البيانات كمصفوفة إلى مكون React المسمى 'Dashboard/Groups/Index'
         * - يتولى الـ Vite تجميع المكون وتقديمه مع البيانات
         * - لا يحتاج إلى Blade View وسيط
         */
        return Inertia::render('Dashboard/Groups/Index', [
            'groups'    => $groups,      // المجموعات مع خدماتها
            'services'  => $services,    // كل الخدمات المتاحة (للقائمة المنسدلة)
            'store_url' => route('Groups.store'), // رابط حفظ مجموعة جديدة
        ]);
    }

    /**
     * ============================================================
     * store() - حفظ مجموعة جديدة مع خدماتها
     * ============================================================
     * 
     * ما وظيفة هذه الدالة؟
     * 1. استقبال البيانات من الـ Request (بعد التحقق من صحتها)
     * 2. حساب الإجماليات (subtotal, بعد الخصم, مع الضريبة)
     * 3. إنشاء سجل جديد في جدول groups
     * 4. ربط الخدمات المختارة مع المجموعة في جدول pivot
     * 5. حفظ سعر الخدمة وقت الشراء في pivot (unit_price)
     * 
     * @param StoreGroupRequest $request - طلب مع قواعد التحقق المدمجة
     * @return RedirectResponse - إعادة توجيه المستخدم إلى صفحة الفهرس
     */
    public function store(StoreGroupRequest $request): RedirectResponse
    {
        /**
         * الخطوة 1: استخراج البيانات من الـ Request
         * 
         * $request->validated()
         * - يجلب البيانات بعد تطبيق قواعد التحقق من StoreGroupRequest
         * - يضمن أن البيانات آمنة وصحيحة قبل المعالجة
         */
        $data = $request->validated();
        
        /**
         * استخراج القيم من البيانات
         * 
         * $items: قائمة الخدمات المختارة [{service_id:1, quantity:2}, ...]
         * $discount: قيمة الخصم (إذا لم توجد، القيمة الافتراضية 0)
         * $tax: نسبة الضريبة (إذا لم توجد، القيمة الافتراضية 17%)
         * 
         * ?? مشغل null coalescing في PHP 7+
         * - إذا كان المفتاح موجوداً وغير null، استخدمه
         * - وإلا استخدم القيمة الافتراضية بعد ??
         */
        $items    = $data['items'];
        $discount = $data['discount'] ?? 0;
        $tax      = $data['tax_percent'] ?? 17;

        /**
         * الخطوة 2: حساب الإجمالي الفرعي (subtotal)
         * 
         * collect($items)
         * - تحويل المصفوفة إلى Collection من Laravel
         * - يسمح باستخدام دوال مثل sum(), map(), filter()
         * 
         * ->sum(function ($item) { ... })
         * - يجمع قيم الإرجاع من الدالة لكل عنصر
         * - الـ function تسمى لكل خدمة في القائمة
         * 
         * Service::find($item['service_id'])
         * - ⚠️ ملاحظة: هذه مشكلة أداء (N+1)
         * - في حالة وجود 10 خدمات، سيتم 10 استعلامات منفصلة
         * - الأفضل: جلب كل الخدمات في استعلام واحد مسبقاً
         */
        $subtotal = collect($items)->sum(function ($item) {
            $service = Service::find($item['service_id']);
            return $service->price * $item['quantity'];  // سعر الخدمة × الكمية
        });

        /**
         * الخطوة 3: حساب الإجمالي بعد الخصم
         * 
         * المعادلة: الإجمالي بعد الخصم = الإجمالي الفرعي - قيمة الخصم
         * مثال: 500 - 50 = 450
         */
        $afterDiscount = $subtotal - $discount;
        
        /**
         * الخطوة 4: حساب الإجمالي النهائي مع الضريبة
         * 
         * المعادلة: الإجمالي النهائي = الإجمالي بعد الخصم × (1 + الضريبة/100)
         * 
         * مثال: الضريبة 17%
         * - 17/100 = 0.17
         * - 1 + 0.17 = 1.17
         * - 450 × 1.17 = 526.5
         */
        $total = $afterDiscount * (1 + $tax / 100);

        /**
         * الخطوة 5: إنشاء سجل المجموعة في قاعدة البيانات
         * 
         * Group::create([...])
         * - Mass assignment: إدخال البيانات مباشرة
         * - يجب أن تكون الحقول في fillable في موديل Group
         * 
         * لماذا نستخدم create بدلاً من new + save؟
         * - create: أقل كتابة وأوضح
         * - لكن يجب أن تكون الحقول معرفة في $fillable (أمان)
         */
        $group = Group::create([
            'name'        => $data['name'],
            'notes'       => $data['notes'] ?? null,  // إذا لم توجد، تكون null
            'subtotal'    => $subtotal,
            'discount'    => $discount,
            'tax_percent' => $tax,
            'total'       => $total,
        ]);

        /**
         * الخطوة 6: تجهيز بيانات الربط (pivot)
         * 
         * نحتاج لربط كل خدمة مع المجموعة
         * جدول pivot (service_group) يحتوي على:
         * - group_id (المجموعة الحالية)
         * - service_id (الخدمة)
         * - quantity (الكمية)
         * - unit_price (سعر الخدمة وقت الشراء)
         * 
         * لماذا نخزن unit_price في pivot؟
         * - السعر في جدول services قد يتغير مستقبلاً
         * - نريد حفظ السعر الذي تمت به الصفقة للحفاظ على الدقة
         * - مثال: خدمة سعرها 100 ريال، بعد شهر أصبحت 120 ريال
         *   الفواتير القديمة تحتفظ بالسعر 100 ريال
         */
        $syncData = [];
        foreach ($items as $item) {
            $service = Service::find($item['service_id']);
            
            $syncData[$item['service_id']] = [
                'quantity'   => $item['quantity'],
                'unit_price' => $service->price,  // حفظ السعر الحالي
            ];
        }

        /**
         * الخطوة 7: ربط الخدمات بالمجموعة
         * 
         * $group->services()->sync($syncData)
         * - sync(): إضافة علاقات Many-to-Many
         * - إذا كانت هناك علاقات سابقة، سيتم حذفها وإضافة الجديدة
         * - sync لا يعمل مثل attach، لأنه يستبدل كل العلاقات
         * 
         * الفرق بين attach و sync:
         * - attach(): يضيف فقط (قد يسبب تكرار)
         * - sync(): يستبدل بالكامل (يحذف القديم ويضيف الجديد)
         * 
         * في هذه الحالة، المجموعة جديدة، فـ sync و attach متساويان
         */
        $group->services()->sync($syncData);

        /**
         * الخطوة 8: إعادة التوجيه مع رسالة نجاح
         * 
         * redirect()->route('Groups.index')
         * - يعيد المستخدم إلى صفحة عرض كل المجموعات
         * 
         * ->with('flash', [...])
         * - يخزن رسالة في الجلسة (session flash data)
         * - تظهر للمستخدم في الصفحة التالية
         * - تختفي بعد ظهورها مرة واحدة
         * 
         * نوع الرسالة (type) يحدد لونها في الـ Frontend:
         * - success: أخضر (نجاح)
         * - error: أحمر (خطأ)
         * - warning: أصفر (تحذير)
         * - info: أزرق (معلومات)
         */
        return redirect()->route('Groups.index')->with('flash', [
            'type'    => 'success',
            'message' => 'Group created successfully',
        ]);
    }

    /**
     * ============================================================
     * destroy() - حذف مجموعة مع خدماتها
     * ============================================================
     * 
     * ما وظيفة هذه الدالة؟
     * - حذف مجموعة محددة من قاعدة البيانات
     * - قبل الحذف، ننظف العلاقات في جدول pivot
     * 
     * @param Group $group - Route Model Binding (يجلب المجموعة تلقائياً)
     * @return RedirectResponse - إعادة توجيه إلى صفحة الفهرس
     */
    public function destroy(Group $group): RedirectResponse
    {
        /**
         * الخطوة 1: تنظيف جدول pivot أولاً
         * 
         * $group->services()->detach()
         * - حذف جميع العلاقات المرتبطة بهذه المجموعة من جدول service_group
         * - بدون هذه الخطوة، سيبقى في pivot data "يتيمة" (orphaned)
         * - هذه البيانات ستصبح بلا معنى لأن group_id لم يعد موجوداً
         * 
         * لماذا نحتاج detach قبل delete؟
         * - قاعدة البيانات لديها foreign key constraint
         * - إذا كان هناك cascadeOnDelete() في الـ migration
         *   قد لا تحتاج لـ detach (سيحذف تلقائياً)
         * - لكن وجود detach صريح أفضل للتوثيق والأمان
         */
        $group->services()->detach();
        
        /**
         * الخطوة 2: حذف المجموعة نفسها
         * 
         * $group->delete()
         * - حذف السجل من جدول groups
         * - Soft delete: إذا كان الموديل يستخدم SoftDeletes
         *   سيضع قيمة في deleted_at بدلاً من الحذف الفعلي
         */
        $group->delete();

        /**
         * الخطوة 3: إعادة التوجيه مع رسالة نجاح
         * 
         * نفس فكرة store ولكن بنوع مختلف من الرسائل
         */
        return redirect()->route('Groups.index')->with('flash', [
            'type'    => 'success',
            'message' => 'Group deleted successfully',
        ]);
    }
}

/**
 * ============================================================
 * ملخص الدوال المستخدمة
 * ============================================================
 * 
 * دوال Eloquent:
 * - with()       : تحميل مسبق للعلاقات (Eager Loading)
 * - select()     : تحديد الأعمدة المطلوبة
 * - latest()     : ترتيب تنازلي حسب created_at
 * - where()      : فلترة البيانات
 * - find()       : البحث عن سجل بمعرف معين
 * - create()     : إنشاء سجل جديد (Mass Assignment)
 * - delete()     : حذف سجل
 * - detach()     : حذف علاقة Many-to-Many
 * - sync()       : مزامنة علاقات Many-to-Many (استبدال)
 * 
 * دوال الـ Collection:
 * - collect()    : تحويل مصفوفة إلى Collection
 * - sum()        : جمع قيم الإرجاع من دالة لكل عنصر
 * - map()        : تحويل كل عنصر إلى شكل جديد
 * - pluck()      : استخراج قيم عمود معين
 * 
 * دوال Laravel عامة:
 * - route()      : إنشاء رابط بناءً على اسم الـ route
 * - redirect()   : توجيه المستخدم
 * - back()       : العودة إلى الصفحة السابقة
 * - with()       : إضافة بيانات Flash للجلسة
 * 
 * ============================================================
 * تحسينات مقترحة لتطوير الكود
 * ============================================================
 * 
 * 1. مشكلة الأداء في حساب subtotal:
 *    - استبدال Service::find داخل الحلقة بـ whereIn
 *    - جلب جميع الخدمات في استعلام واحد
 * 
 * 2. إضافة المعالجة للقيم السالبة:
 *    - $afterDiscount = max(0, $subtotal - $discount);
 * 
 * 3. استخدام DTO (Data Transfer Object):
 *    - إنشاء كائن GroupData بدلاً من تمرير مصفوفة
 * 
 * 4. إضافة التوثيق PHPDoc كاملاً:
 *    - @throws, @see, @since
 * 
 * 5. استخدام Cache للخدمات المتاحة:
 *    - Services rarely change, cache them
 */