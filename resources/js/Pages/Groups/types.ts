export interface GroupItem {
  service_id: number | '';
  quantity: number;
  is_saved: boolean;
  service_name: string;
  unit_price: number;
}

export interface GroupURLs {
  update: string;
  delete: string;
  store: string;
}

export interface Group {
  id: number;
  name: string;
  notes: string | null;
  subtotal: string;
  discount: string;
  tax_percent: string;
  total: string;
  is_active: boolean;
  created_at: string;
  services: {
    id: number;
    name: string;
    quantity: number;
    unit_price: string;
  }[];
  urls: GroupURLs;
  url_store: string;
}
export interface Service {
    id: number;
    name: string;
    description: string | null;
    price: string;
    is_active: boolean;
}

export interface GroupFormData {
  name: string;
  notes: string;
  discount: number;
  tax_percent: number;
  items: {
    service_id: number;
    quantity: number;
  }[];
}