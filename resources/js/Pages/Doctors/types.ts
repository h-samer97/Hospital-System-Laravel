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

export interface Doctor {
    id: number;
    section_id: number;
    name: string;
    email: string;
    phone: string;
    price: number | string | null;
    is_active: boolean;
    created_at: string;
    appointments: string;
    section: Section;
    image: Image | null;
    image_url: string | null;
    edit_url: string;
    delete_url: string;
    store_url: string;
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
    section_id: string | number;
    name: string;
    appointments: string;
    email: string;
    password: string;
    phone: string;
    price: string;
    image: File | null;
}

export type DoctorSection = Section;