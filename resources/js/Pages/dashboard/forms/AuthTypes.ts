export type UserRole = 'user' | 'admin' | 'doctor' | 'ray_employee' | 'laboratorie_employee';

export interface RoleConfig {
    label: string;
    title: string;
    apiEndpoint: string;
}