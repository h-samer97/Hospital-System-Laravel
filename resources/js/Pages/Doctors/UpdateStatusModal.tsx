import { useForm } from "@inertiajs/react";
import styles from './Modal.module.css';
import { Doctor, UpdateStatusForm } from "./types";
import { FormEvent } from 'react';

interface Props {
    doctor: Doctor;
    onClose: () => void;
}

export default function UpdateStatusModal({ doctor, onClose }: Props) {
    
    const {errors, data, setData, processing, patch} = useForm<UpdateStatusForm>({
        is_active: doctor.is_active,
    });

    function handleSubmit(e: FormEvent<HTMLFormElement>) {

        e.preventDefault();
        patch(doctor.update_status_url, {
            onSuccess: () => {
                onClose();
            }
        })

    }

    return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={`${styles.modal} ${styles.deleteModal}`}
        onClick={(e) => e.stopPropagation()}>

        <div className={styles.modalHeader}>
          <h2>Update Status — {doctor.name}</h2>
          <button className={styles.closeBtn} onClick={onClose}>✕</button>
        </div>

        <form onSubmit={handleSubmit}>
          <div className={styles.modalBody}>

            <div className={styles.formGroup}>
              <label>Status</label>
              <select
                value={data.is_active ? '1' : '0'}
                onChange={(e) => setData('is_active', e.target.value === '1')}
                className={styles.input}
              >
                <option value="" disabled>-- Choose --</option>
                <option value="1">✅ Active</option>
                <option value="0">❌ Inactive</option>
              </select>
              {errors.is_active && (
                <span className={styles.errorMsg}>{errors.is_active}</span>
              )}
            </div>

          </div>

          <div className={styles.modalFooter}>
            <button type="button" className={styles.cancelBtn} onClick={onClose}>
              Cancel
            </button>
            <button type="submit" className={styles.submitBtn} disabled={processing}>
              {processing ? 'Saving...' : 'Update Status'}
            </button>
          </div>
        </form>

      </div>
    </div>
  );


}
