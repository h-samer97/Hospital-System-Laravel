export type RoleKey = "user" | "admin" | "doctor" | "ray_employee" | "pharmacy_employee" | "laboratorie_employee";
export interface RoleConfig {
    label: string,
    action: string,
    icon: string
};
export interface LoginFormData {
  email: string;
  password: string;
  role: RoleKey;
};