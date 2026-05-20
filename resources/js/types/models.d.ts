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

export interface Image {
    id: number;
    url: string;
}

export interface Section {
    id: number;
    name: string;
    is_active?: boolean;
}

export interface Appointment {
    id: number;
    name: string;
}

export interface FlashMessage {
    message: string;
    type: 'success' | 'error' | 'warning';
}
