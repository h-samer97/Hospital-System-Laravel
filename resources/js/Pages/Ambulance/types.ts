// تعريف واحد فقط — أسماء متسقة مع Laravel
export interface urls {
  update:  string;   // route('ambulances.update', id)
  destroy: string;   // route('ambulances.destroy', id)
}

export interface Ambulance {
  id:                    number;
  car_number:            string;
  car_model:             string;
  car_year_made:         number;
  car_type:              'owned' | 'rental';
//   car_type_label:        string;
  driver_name:           string;
  driver_license_number: string;
  driver_phone:          string;
  is_available:          boolean;
  notes:                 string | null;
  status:                boolean;
  created_at:            string;
  urls:                  urls;
}

export interface IndexProps {
  ambulances: Ambulance[];
  store_url:  string;   // ← منفصل — route('ambulances.store')
}

// TODO