export interface insurances {
  name: string;
  id: number;
  note?: string;
  discount_percentage: number;
  insurance_code: string;
  company_rate: number;
  is_active: boolean;
  created_at: Date;
  url_store: string;
  urls: {
    update: string;
    destroy: string;
  }
}

export interface InsuranceFormData {
  name:                 string;
  note:                 string;
  insurance_code:       string | number;
  discount_percentage:  string | number;
  company_rate:         string | number;
  is_active:            boolean;
}

// TODO