export interface Payment {
  id:          number;
  date:        string;
  patient:     string | null;
  patient_id:  number;
  amount:      string;
  description: string;
  created_at:  string;
  urls: {
    print?:    string;
    download?: string;
    update:  string;
    destroy: string;
  };
}

export interface PaymentFormData {
  patient_id:  number | '';
  amount:      string;
  description: string;
}