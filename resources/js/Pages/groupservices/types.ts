export interface ServiceItem {
    service_id: string;
    service_name: string;
    service_price: number;
    quantity: number;
    is_saved: boolean;
}

export interface ServiceGroup {
    name_group: string;
    notes: string;
    items: ServiceItem[];
    discount_value: number;
    taxes: number; // نسبة الضريبة
}