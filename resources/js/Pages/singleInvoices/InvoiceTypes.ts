export interface SingleInvoice {
    id: number;
    service_name: string; // اسم الخدمة
    patient_name: string; // اسم المريض
    invoice_date: string; // تاريخ الفاتورة
    doctor_name: string;  // اسم الدكتور
    section_name: string; // القسم
    price: number;        // سعر الخدمة
    discount_value: number; // قيمة الخصم
    tax_rate: number;     // نسبة الضريبة
    tax_value: number;    // قيمة الضريبة
    total_with_tax: number; // الاجمالي مع الضريبة
    type: 1 | 2;          // 1: نقدي، 2: أجل
}