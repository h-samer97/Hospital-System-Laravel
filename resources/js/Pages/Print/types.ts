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