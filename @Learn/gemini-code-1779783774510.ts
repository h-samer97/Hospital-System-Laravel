import React from 'react';
import { useForm } from '@inertiajs/react';

// 1. تعريف واجهة البيانات للسجل القادم من قاعدة البيانات لضمان الـ Type-Safety
export interface Insurance {
    id: number;
    insurance_code: string;
    name: string;
    discount_percentage: number;
    company_rate: number;
    status: boolean;
    notes?: string;
}

export default function Index() {
    
    // 2. إعداد خطاف useForm التابع لـ Inertia
    // أفضل ممارسة: نحدد القيم الابتدائية كـ Strings لأن مدخلات الـ HTML (Inputs) تتعامل مع النصوص بشكل طبيعي،
    // ونترك للارافيل مهمة الـ Casting أو نقوم بالتحويل عند الحاجة لمنع مشاكل الـ Uncontrolled Inputs في React.
    const { data, setData, post, put, errors, reset, processing } = useForm({
        insurance_code: '',
        name: '',
        discount_percentage: '',
        company_rate: '',
        notes: '',
        status: true, // حالة مبدئية نشطة للـ Checkbox
    });

    // 3. دالة معالجة إرسال النموذج (Form Submission)
    // استخدمنا React.FormEvent<HTMLFormElement> لمنع أي نوع بيانات عشوائي (Any) ولتأمين الـ Autocomplete للـ Events في المحرر.
    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault(); // منع السلوك الافتراضي للمتصفح بإعادة تحميل الصفحة

        /**
         * 💡 شرح ممارسة هندسية متقدمة (HTMLFormCollection Casting):
         * إذا أردت قراءة عناصر الفورم برمجياً وبشكل صارم ومباشر من الـ DOM دون الاعتماد فقط على الـ State،
         * نقوم بعمل كاستينغ لعناصر الفورم الحالية إلى HTMLFormCollection كالتالي:
         */
        const formElements = e.currentTarget.elements as HTMLFormCollection;
        
        // مثال برميجي: لو أردنا الوصول لعنصر معرّف باسم معين داخل عناصر الفورم بشكل مباشر وآمن:
        // const codeInput = formElements.namedItem('insurance_code') as HTMLInputElement;
        // console.log(codeInput.value);

        // تحديد ما إذا كانت العملية إضافة (Store) أم تحديث (Update) بناءً على وجود معرف (ID) مثلاً
        // في هذا الجزء سنعتمد مسار الإضافة كمثال مباشر:
        post('/insurance', {
            // أفضل ممارسة: تفعيل مؤشر الانتظار ومسح الحقول فقط عند نجاح العملية بالكامل بالسيرفر
            onSuccess: () => {
                reset(); // إعادة تعيين الحقول إلى قيمها الابتدائية بعد الحفظ بنجاح
                alert('تم حفظ شركة التأمين بنجاح!');
            },
            onError: (backendErrors) => {
                // هنا يمكنك معالجة الأخطاء القادمة من StoreInsuranceRequest في لارافيل
                console.error("فشل التحقق من البيانات في السيرفر:", backendErrors);
            },
            preserveScroll: true, // يمنع الصفحة من القفز للأعلى بعد عمل ريفريش للـ Props بواسطة Inertia
        });
    };

    return (
        // تم تجريد كود الـ TSX ليوضح لك فقط طريقة ربط الدالة بالـ Form
        <form onSubmit={handleSubmit}>
            {/* مثال لربط الحقول مع الـ useForm والـ Errors */}
            <div>
                <label>كود الشركة:</label>
                <input 
                    type="text" 
                    name="insurance_code"
                    value={data.insurance_code} 
                    onChange={e => setData('insurance_code', e.target.value)} 
                />
                {errors.insurance_code && <span className="error">{errors.insurance_code}</span>}
            </div>

            <button type="submit" disabled={processing}>
                {processing ? 'جاري الحفظ...' : 'حفظ البيانات'}
            </button>
        </form>
    );
}