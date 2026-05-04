# البيانات الوهمية المتكاملة - Hospital Project

تم إنشاء بيانات وهمية متكاملة لجميع أقسام المشروع بنجاح.

## الملفات المُنشأة

### 1. Factories (مصانع البيانات)
تم إنشاء factories متكاملة لجميع المودلات:

- **PatientFactory** - توليد بيانات المرضى
  - الاسم، البريد الإلكتروني، كلمة المرور
  - تاريخ الميلاد، الهاتف، الجنس
  - فئة الدم، العنوان

- **AdminFactory** - توليد بيانات الإداريين
  - الاسم، البريد الإلكتروني، كلمة المرور

- **SingleServicesFactory** - توليد الخدمات الطبية
  - 12 خدمة طبية مختلفة (فحوصات، أشعات، اختبارات، لقاحات، إلخ)
  - السعر لكل خدمة

- **InsuranceFactory** - توليد شركات التأمين
  - 8 شركات تأمين بأسماء مختلفة
  - رموز التأمين، نسب الخصم، معدل الشركة

- **AmbulancesFactory** - توليد سيارات الإسعاف
  - بيانات السيارة (الرقم، الموديل، السنة)
  - بيانات السائق (الاسم، الرخصة، الهاتف)
  - حالة التوفر، نوع السيارة

- **AppointmentFactory** - توليد المواعيد
  - بيانات المواعيد الافتراضية

- **SingleInvioceFactory** - توليد الفواتير
  - السعر، الخصم، ضريبة
  - الإجمالي مع الضريبة
  - ارتباطات مع المرضى والأطباء والأقسام والخدمات

### 2. Seeders (بذور قاعدة البيانات)

تم إنشاء seeders متكاملة:

- **PatientSeeder** - ملء بيانات المرضى (15 سجل)
- **SingleServicesSeeder** - ملء بيانات الخدمات (12 خدمة)
- **InsuranceSeeder** - ملء بيانات التأمين (8 شركات)
- **AmbulancesSeeder** - ملء بيانات الإسعافات (10 سيارات)
- **SingleInvoiceSeeder** - ملء بيانات الفواتير (20 فاتورة)

## إحصائيات البيانات المدرجة

تم ملء قاعدة البيانات بالبيانات التالية:

| الجدول | العدد | الوصف |
|-------|------|-------|
| Users | 1 | المستخدم الافتراضي |
| Admins | 1 | مسؤول افتراضي (root) |
| Sections | 21 | الأقسام الطبية (7 قسم أساسي + 14 إضافي) |
| Appointments | 7 | أيام الأسبوع (السبت - الجمعة) |
| Single Services | 36 | الخدمات الطبية المختلفة |
| Doctors | 30 | الأطباء (10 أساسي + إضافي من العلاقات) |
| Patients | 45 | المرضى (15 أساسي + إضافي من الفواتير) |
| Insurance | 16 | شركات التأمين (8 أساسي + إضافي) |
| Ambulances | 20 | سيارات الإسعاف (10 أساسي + إضافي) |
| Single Invoices | 20 | الفواتير الطبية |
| Groups | 20 | مجموعات الخدمات |
| Images | 20 | الصور المرتبطة بالوحدات |

## تشغيل الـ Seeders

لتشغيل جميع البذور:

```bash
php artisan db:seed
```

لتشغيل سيدر واحد فقط:

```bash
php artisan db:seed --class=Database\\Seeders\\PatientSeeder
```

لإعادة تعيين قاعدة البيانات وتشغيل الهجرات والبذور:

```bash
php artisan migrate:refresh --seed
```

## بيانات تسجيل الدخول الافتراضية

### Admin
- البريد: `root@example.com`
- كلمة المرور: `root`

### User
- البريد: `user@example.com`
- كلمة المرور: `root`

## ملاحظات مهمة

1. جميع الـ factories توليد بيانات عشوائية باستخدام Faker
2. الأسعار والخصومات والضرائب محسوبة بطريقة واقعية
3. البيانات مترابطة بشكل صحيح (Foreign Keys)
4. جميع المودلات تستخدم HasFactory trait
5. الـ Seeders تعمل بترتيب محدد لضمان عدم وجود خطأ في العلاقات

## تحديثات أجريت على المشروع

### Models
- أضيف `HasFactory` trait لـ: Patient, Insurance, Ambulances, Appointment, SingleInvioce
- تعديل `Patient` model: تغيير `$timestamps` من `protected` إلى `public`

### Factories الجديدة
- PatientFactory
- AdminFactory
- SingleServicesFactory
- InsuranceFactory
- AmbulancesFactory
- AppointmentFactory
- SingleInvioceFactory

### Seeders الجديدة
- PatientSeeder
- SingleServicesSeeder
- InsuranceSeeder
- AmbulancesSeeder
- SingleInvoiceSeeder

### تحديث DatabaseSeeder
تم تحديث `DatabaseSeeder.php` لتضمين جميع الـ seeders الجديدة بالترتيب الصحيح.
