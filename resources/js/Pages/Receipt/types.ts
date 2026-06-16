// ===== Receipt Account =====
export interface Receipt {
  id:          number;
  date:        string;
  patient:     string | null;
  patient_id:  number;
  debit:       string;        // decimal → string من PHP
  description: string;
  created_at:  string;
  urls: {
    update:  string;
    destroy: string;
  };
}

export interface ReceiptFormData {
  patient_id:  number | '';
  debit:       string;
  description: string;
}

// Pagination من Laravel
export interface PaginatedData<T> {
  data:          T[];
  current_page:  number;
  last_page:     number;
  per_page:      number;
  total:         number;
  next_page_url: string | null;
  prev_page_url: string | null;
}