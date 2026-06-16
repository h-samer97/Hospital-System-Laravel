// TODO
// Doctor Form Data {}

export interface Image {
    id: number;
    url: string;
}

export interface Section {
    id: number;
    name: string;
}

export interface Appointment {
    id: number;
    name: string;
}

export interface Doctor {
    id: number;
    section_id: number;
    name: string;
    email: string;
    phone: string;
    price: number | string | null;
    is_active: boolean;
    created_at: string;
    appointments: Appointment[];
    section: Section;
    image: Image | null;
    image_url: string | null;
    edit_url: string;
    delete_url: string;
    store_url: string;
    update_password_url: string;
    update_status_url: string;
}

export interface UpdatePasswordForm {
    password: string;
    password_confirmation: string;
}

export interface UpdateStatusForm {
    is_active: boolean;
}

export interface StoreDoctorRequest {
    name: string;
    email: string;
    phone: string;
    price: number;
    password: string;
    is_active?: boolean;
    section_id: number;
    appointments: string;
    image?: File;
}

export interface UpdateDoctorRequest {
    id: number;
    name?: string;
    email?: string;
    phone?: string;
    price?: number;
    password?: string;
    is_active?: boolean;
    section_id?: number;
    appointments?: string;
    image?: File;
}

export interface ApiResponse {
    message: string;
}

export interface DoctorFormData {
    id?: number | string;
    section_id: string | number;
    name: string;
    appointment_ids: number[];
    email: string;
    password: string;
    phone: string;
    price: string;
    image: File | null;
}

export type DoctorSection = Section;

export const DAYS = [
    'Saturday',
    'Sunday',
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
] as const;