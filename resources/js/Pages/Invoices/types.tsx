export type InvoiceType = 'cash' | 'deferred';

export interface InvoiceDoctor {
  id: number;
  name: string;
  section_id: number;
  section_name: string;
}

export interface SingleInvoice {
  id: number;
  invoice_date: string;
  patient_id?: number | null;
  patient: string | null;
  doctor_id?: number | null;
  doctor: string | null;
  section_id?: number | null;
  section: string | null;
  service_id?: number | null;
  service: string | null;
  price: string;
  discount_value: string;
  tax_rate: string;
  tax_value: string;
  total_with_tax: string;
  type: InvoiceType;
  type_label: string;
  created_at: string;
  urls: {
    update: string;
    destroy: string;
    print?:    string;
    download?: string;
  };
}

export interface InvoiceFormData {
  patient_id: number | '';
  doctor_id: number | '';
  section_id: number | '';
  service_id: number | '';
  price: number | '';
  discount_value: number;
  tax_rate: number;
  type: InvoiceType | '';
}

export interface InvoiceCalculation {
  subtotal: number;
  tax_value: number;
  total: number;
}