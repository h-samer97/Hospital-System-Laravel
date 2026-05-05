export interface Doctor {
    id?: number;
    name: string;
    email: string;
    password?: string;
    password_confirmation?: string;
    phone: string;
    section_id: number | string;
    appointments: string[]; // لتخزين المواعيد المتعددة
    price: number | string;
    status: number; // 1: مفعل, 0: غير مفعل
    image?: { filename: string };
    photo?: File; // لعملية الرفع
    created_at?: string;
}

export interface Section {
    id: number;
    name: string;
}