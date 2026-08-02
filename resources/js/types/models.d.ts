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

export interface PrintablePayment {
  id:          number;
  date:        string;
  patient:     string | null;
  phone:       string | null;
  address:     string | null;
  amount:      string;
  description: string;
  created_at:  string;
}

export interface PrintableReceipt {
  id:          number;
  date:        string;
  patient:     string | null;
  phone:       string | null;
  address:     string | null;
  debit:       string;
  description: string;
  created_at:  string;
}

export interface PrintableInvoice {
  id:             number;
  invoice_date:   string;
  patient:        string | null;
  phone:          string | null;
  doctor:         string | null;
  section:        string | null;
  service:        string | null;
  price:          string;
  discount_value: string;
  tax_rate:       string;
  tax_value:      string;
  total_with_tax: string;
  type:           'cash' | 'deferred';
  type_label:     string;
  created_at:     string;
}
