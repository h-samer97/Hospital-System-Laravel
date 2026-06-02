# 📋 الشفرة التنفيذية الموحدة: نظام إدارة المرضى (Patients CRUD)

**البيئة البرمجية:** Laravel 11+ | React.js (TypeScript) | Inertia.js (بدون Ziggy، بدون حزم ترجمة، وبدون Bootstrap).

---

## 🏗️ 1. طبقة البنية التحتية وقاعدة البيانات (Database Layer)

### أ. ملف الهجرة: `database/migrations/xxxx_xx_xx_create_patients_table.php`
دمج حقول الاسم والعنوان مباشرة في الجدول الأساسي وإلغاء جداول الترجمة المنفصلة لضمان استعلامات سريعة وتفادي الـ Joins الزائدة.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تفعيل الهجرة وبناء الجدول الأساسي مدمجاً.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // الاسم مدمج مباشرة بالجدول
            $table->string('email')->unique(); // البريد الإلكتروني فريد لمنع تكرار الحسابات ثغرةً وأماناً
            $table->string('password'); // كلمة المرور للحساب مشفرة
            $table->string('phone'); // رقم الهاتف للاتصال
            $table->date('birth_date'); // أفضل ممارسة: استخدام النوع الصريح date بدلاً من string لتأمين البيانات
            $table->string('gender'); // تحديد الجنس (male / female)
            $table->string('blood_group'); // زمرة الدم الطبية للمريض
            $table->string('address'); // العنوان مدمج مباشرة في السجل الأساسي
            $table->timestamps(); // أوقات الإنشاء والتحديث للتتبع الزمني
        });
    }

    /**
     * إلغاء الجدول في حال التراجع عن الهجرة.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /**
     * الحقول القابلة للتعبئة الجماعية لحماية النظام من ثغرات الحقن العشوائي Mass Assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birth_date',
        'gender',
        'blood_group',
        'address'
    ];

    /**
     * أفضل ممارسة لـ Laravel 11: التلاعب بالأنواع برمجياً (Attribute Casting عبر تابع casts).
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date:Y-m-d', // ضمان تنسيق التاريخ بدقة قبل نقله إلى الـ React
            'password' => 'hashed', // يضمن تشفير كلمة المرور يدوياً وتلقائياً فور الحفظ أو التعديل برمجياً
        ];
    }
}

<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
{
    /**
     * التحقق من صلاحية المستخدم لإجراء الطلب البرمجي.
     */
    public function authorize(): bool
    {
        return true; // تفعيل الفحص التلقائي لكافة الطلبات الممررة عبر هذا الكلاس
    }

    /**
     * قواعد التحقق الصارمة لبيانات المريض ومطابقتها للمواصفات الطبية.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                // ممارسة احترافية لبيئة الإنتاج: نستخدم اسم فئة المريض لتفادي نصوص الجداول المصلّبة
                // ونقوم باستخراج المعرف id من الـ Route لتأمين الاستثناء في التحديث (Implicit/Explicit Model Binding)
                Rule::unique(\App\Models\Patient::class, 'email')->ignore($this->route('patient')?->id ?? $this->route('patient'))
            ],
            // أفضل ممارسة واقعية: الباسورد إلزامي عند الإضافة (POST) واختياري عند التحديث (PUT) لتفادي إجبار الموظف على التعديل
            'password' => $this->isMethod('post') ? ['required', 'string', 'min:8'] : ['nullable', 'string', 'min:8'],
            'phone' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:male,female'], // تقييد المدخلات لخيارات منطقية صارمة في قاعدة البيانات
            'blood_group' => ['required', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'], // حصر الفحص بزمر الدم المعروفة طبياً
            'address' => ['required', 'string', 'max:500'],
        ];
    }
}

<?php

namespace App\Interfaces\Patients;

interface PatientRepositoryInterface
{
    public function index();
    public function store($request);
    public function edit($id);
    public function update($request, $id);
    public function destroy($id);
}