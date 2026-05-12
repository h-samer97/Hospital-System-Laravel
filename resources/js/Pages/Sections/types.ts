export interface Section {
  id: number;
  name: string;
  name_ar: string;
  is_active: boolean;
  created_at: string;
  updated_at?: string;
  deleted_at?: string | null;
}
export interface SectionForm {
  name: string;
  is_active?: boolean;
}
export interface flashMessage {
  message: string;
  type: 'success' | 'error' | 'warning';
}