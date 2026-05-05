export interface Section {
    id?: number;
    name: string; // اسم القسم
    created_at?: string; // تاريخ الإنشاء
}

export interface Doctor {
    id: number;
    name: string;
    email: string;
    section: { name: string };
    phone: string;
    appointments: { id: number; name: string }[];
    status: number; // 1: مفعل، 0: غير مفعل
}