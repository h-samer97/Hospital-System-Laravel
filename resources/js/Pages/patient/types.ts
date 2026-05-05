export interface Patient {
    id?: number;
    name: string; // اسم المريض
    email: string; // البريد الإلكتروني
    Date_Birth: string; // تاريخ الميلاد
    Phone: string; // رقم الهاتف
    Gender: number; // 1: ذكر، 2: أنثى
    Blood_Group: string; // فصيلة الدم
    Address?: string; // العنوان
}