import type { Patient, BloodGroup } from "@/Pages/Patients/types";

// ===== Patient Financial Summary =====
export interface PatientFinancialSummary {
  total_invoiced:  number;
  total_receipts:  number;
  total_payments:  number;
  debit_total:     number;
  credit_total:    number;
  balance:         number;
  balance_label:   'Debtor' | 'Creditor' | 'Settled';
  is_debtor:       boolean;
}

// ===== Patient Ledger Entry =====
export type LedgerEntryType = 'invoice' | 'receipt' | 'payment' | 'other';

export interface LedgerEntry {
  id:              number;
  date:            string;
  description:     string;
  entry_type:      LedgerEntryType;
  debit:           number;
  credit:          number;
  running_balance: number;
  is_debtor:       boolean;
}

// ===== Invoice in Patient Show =====
export interface PatientInvoice {
  id:             number;
  invoice_date:   string;
  service:        string | null;
  doctor:         string | null;
  price:          string;
  discount_value: string;
  tax_value:      string;
  total_with_tax: string;
  type:           'cash' | 'deferred';
  type_label:     string;
  print_url:      string;
}

// ===== Receipt in Patient Show =====
export interface PatientReceipt {
  id:          number;
  date:        string;
  debit:       string;
  description: string;
  print_url:   string;
}

export interface PatientProfile { 
  id: number;
  name: string;
  email: string;
  phone: string;
  date_birth: string;
  age: number;
  gender: 'male' | 'famale';
  gender_label: string;
  blood_type: BloodGroup;
  address: string;
  is_active: boolean;
  member_since: string 
}

export type { Doctor, PrintablePayment, PrintableReceipt, PrintableInvoice } from './models.d';

export interface PatientShowProps {
  patient: PatientProfile;
  summary: PatientFinancialSummary;
  ledger: LedgerEntry[];
  invoices: PatientInvoice[];
  receipts: PatientReceipt[];

}