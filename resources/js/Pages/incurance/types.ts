export interface Insurance {
    id?: number;
    insurance_code: string; 
    name: string; 
    discount_percentage: number | string; 
    Company_rate: number | string;
    status: number; 
    notes?: string;
}