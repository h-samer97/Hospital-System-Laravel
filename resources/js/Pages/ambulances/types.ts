export interface Ambulance {
    id?: number;
    car_number: string;
    car_model: string;
    car_year_made: string | number;
    car_type: '1' | '2'; // 1: مملوكة, 2: ايجار
    driver_name: string;
    driver_license_number: string | number;
    driver_phone: string | number;
    is_available: boolean;
    notes?: string;
}