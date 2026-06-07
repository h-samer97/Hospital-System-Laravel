interface Patient {
    id: number;
    name: string;
    email: string;
    birth_date: string;
    age: number;
    phone: string;
    gender: 'male' | 'female';
    gender_label: string;
    blood_group: BloodGroup['typeBlood'];
    address: string;
    is_active?: boolean;
    urls: {
        update: string;
        destroy: string;
    };
    created_at?: string;
    updated_at?: string;
}

interface BloodGroup {
    typeBlood: 'A+' | 'A-' | 'B+' | 'B-' | 'AB+' | 'AB-' | 'O+' | 'O-';
}

interface PatientFormData {
    name?: string;
    email?: string;
    password?: string;
    birth_date?: string;
    phone?: string;
    gender?: 'male' | 'female' | string;
    blood_group?: BloodGroup['typeBlood'];
    address: string;
}

export default PatientFormData;
export type { Patient, BloodGroup };