export interface Services {
    name: string;
    id: number;
    description: string | null;
    price: string; // arrived string form php
    created_at: string;
    updated_at: string | null;
    is_active: boolean;
    // URLs
    url_store: string;
    url_delete: string;
    url_update: string;
}

export interface ServiceFormData {
  name: string;
  description: string;
  price: string;
  is_active: boolean;
}