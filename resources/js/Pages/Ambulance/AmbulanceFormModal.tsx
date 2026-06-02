import { useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import type { Ambulance } from './types';
import styles from './FormModal.module.css';

type AmbulanceFormData = {
  car_number:            string;
  car_model:             string;
  car_year_made:         string;
  car_type:              'owned' | 'rental' | '';
  driver_name:           string;
  driver_license_number: string;
  driver_phone:          string;
  is_available:          boolean;
  notes:                 string;
};

interface Props {
  mode:       'add' | 'edit';
  ambulance?: Ambulance | null;
  url_store:  string;
  onClose:    () => void;
}

const FormModal = ({ mode, onClose, ambulance, url_store }: Props) => {
  const isEdit = mode === 'edit';

  const { data, setData, errors, post, processing, reset, put } =
    useForm<AmbulanceFormData>({
      car_number:            ambulance?.car_number            ?? '',
      car_model:             ambulance?.car_model             ?? '',
      // ✅ car_year_made يُحوَّل لـ string لأن input يعمل مع string
      car_year_made:         ambulance?.car_year_made?.toString() ?? '',
      car_type:              ambulance?.car_type              ?? '',
      driver_name:           ambulance?.driver_name           ?? '',
      driver_license_number: ambulance?.driver_license_number ?? '',
      driver_phone:          ambulance?.driver_phone          ?? '',
      is_available:          ambulance?.is_available          ?? true,
      notes:                 ambulance?.notes                 ?? '',
    });

  const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const opts = { onSuccess: () => { reset(); onClose(); } };

    isEdit
      ? put(url_store, opts)
      : post(url_store, opts);
  };

  return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>

        <div className={styles.header}>
          <h2>{isEdit ? 'Edit Ambulance' : 'Add New Ambulance'}</h2>
          <button className={styles.closeBtn} onClick={onClose}>✕</button>
        </div>

        <form onSubmit={handleSubmit}>
          <div className={styles.body}>

            <div className={styles.row}>
              <div className={styles.formGroup}>
                <label>Car Number</label>
                <input type="text"
                  className={errors.car_number ? styles.inputError : styles.input}
                  placeholder="e.g. AMB-001"
                  value={data.car_number}
                  onChange={(e) => setData('car_number', e.target.value)}
                  autoFocus
                />
                {errors.car_number && <span className={styles.msgError}>{errors.car_number}</span>}
              </div>
              <div className={styles.formGroup}>
                <label>Car Model</label>
                <input type="text"
                  className={errors.car_model ? styles.inputError : styles.input}
                  placeholder="e.g. Toyota HiAce"
                  value={data.car_model}
                  onChange={(e) => setData('car_model', e.target.value)}
                />
                {errors.car_model && <span className={styles.msgError}>{errors.car_model}</span>}
              </div>
            </div>

            <div className={styles.row}>
              <div className={styles.formGroup}>
                <label>Year Made</label>
                <input type="number"
                  className={errors.car_year_made ? styles.inputError : styles.input}
                  min="1990" max={new Date().getFullYear()}
                  placeholder="e.g. 2020"
                  value={data.car_year_made}
                  onChange={(e) => setData('car_year_made', e.target.value)}
                />
                {errors.car_year_made && <span className={styles.msgError}>{errors.car_year_made}</span>}
              </div>
              <div className={styles.formGroup}>
                {/* ✅ car_type موجود في الـ migration */}
                <label>Car Type</label>
                <select
                  className={errors.car_type ? styles.inputError : styles.input}
                  value={data.car_type}
                  onChange={(e) => setData('car_type', e.target.value as 'owned' | 'rental')}
                >
                  <option value="">-- Choose --</option>
                  <option value="owned">🏥 Owned</option>
                  <option value="rental">🚗 Rental</option>
                </select>
                {errors.car_type && <span className={styles.msgError}>{errors.car_type}</span>}
              </div>
            </div>

            <div className={styles.row}>
              <div className={styles.formGroup}>
                <label>Driver Name</label>
                <input type="text"
                  className={errors.driver_name ? styles.inputError : styles.input}
                  placeholder="Full name"
                  value={data.driver_name}
                  onChange={(e) => setData('driver_name', e.target.value)}
                />
                {errors.driver_name && <span className={styles.msgError}>{errors.driver_name}</span>}
              </div>
              <div className={styles.formGroup}>
                <label>License Number</label>
                <input type="text"
                  className={errors.driver_license_number ? styles.inputError : styles.input}
                  placeholder="e.g. DL-12345"
                  value={data.driver_license_number}
                  onChange={(e) => setData('driver_license_number', e.target.value)}
                />
                {errors.driver_license_number && <span className={styles.msgError}>{errors.driver_license_number}</span>}
              </div>
            </div>

            <div className={styles.row}>
              <div className={styles.formGroup}>
                <label>Driver Phone</label>
                <input type="text"
                  className={errors.driver_phone ? styles.inputError : styles.input}
                  placeholder="e.g. 01012345678"
                  value={data.driver_phone}
                  onChange={(e) => setData('driver_phone', e.target.value)}
                />
                {errors.driver_phone && <span className={styles.msgError}>{errors.driver_phone}</span>}
              </div>
              <div className={styles.formGroup}>
                <label>Availability</label>
                <select
                  className={styles.input}
                  value={data.is_available ? '1' : '0'}
                  onChange={(e) => setData('is_available', e.target.value === '1')}
                >
                  <option value="1">✅ Available</option>
                  <option value="0">❌ Unavailable</option>
                </select>
              </div>
            </div>

            <div className={styles.formGroup}>
              <label>Notes <small>(optional)</small></label>
              <textarea
                className={styles.input}
                placeholder="Any additional notes..."
                rows={3}
                value={data.notes}
                onChange={(e) => setData('notes', e.target.value)}
              />
            </div>

          </div>

          <div className={styles.footer}>
            <button type="button" className={styles.cancelBtn} onClick={onClose}>
              Cancel
            </button>
            <button type="submit" className={styles.submitBtn} disabled={processing}>
              {processing ? 'Saving...' : isEdit ? 'Update' : 'Add'}
            </button>
          </div>
        </form>

      </div>
    </div>
  );
};

export default FormModal;