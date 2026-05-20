import { useForm } from '@inertiajs/react';
import styles from './Modal.module.css';
import {  Doctor, UpdatePasswordForm } from './types';
import { FormEvent } from 'react';


interface Prop {

    doctor: Doctor;
    onClose: () => void;

}


export default function UpdatePasswordModal({ doctor, onClose }: Prop) {


    const {data, setData, errors, patch, processing, reset} = useForm<UpdatePasswordForm>({
        password: '',
        password_confirmation: '',
    });

    function handleSubmit(e: FormEvent<HTMLFormElement>) {

        e.preventDefault();

        patch(doctor.update_password_url, {
            onSuccess: () => {
                onClose();
            },
            onError: () => {
                // Handle errors
            }
        })

    }


    return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>

        <div className={styles.modalHeader}>
          <h2>Update Password — {doctor.name}</h2>
          <button className={styles.closeBtn} onClick={onClose}>✕</button>
        </div>

        <form onSubmit={handleSubmit}>
          <div className={styles.modalBody}>

            <div className={styles.formGroup}>
              <label>New Password</label>
              <input
                type="password"
                value={data.password}
                onChange={(e) => setData('password', e.target.value)}
                className={errors.password ? styles.inputError : styles.input}
                placeholder="Min 8 characters"
                autoFocus
              />
              {errors.password && (
                <span className={styles.errorMsg}>{errors.password}</span>
              )}
            </div>

            <div className={styles.formGroup}>
              <label>Confirm Password</label>
              <input
                type="password"
                value={data.password_confirmation}
                onChange={(e) => setData('password_confirmation', e.target.value)}
                className={errors.password_confirmation ? styles.inputError : styles.input}
                placeholder="Repeat password"
              />
              {errors.password_confirmation && (
                <span className={styles.errorMsg}>{errors.password_confirmation}</span>
              )}
            </div>

          </div>

          <div className={styles.modalFooter}>
            <button type="button" className={styles.cancelBtn} onClick={onClose}>
              Cancel
            </button>
            <button type="submit" className={styles.submitBtn} disabled={processing}>
              {processing ? 'Saving...' : 'Update Password'}
            </button>
          </div>
        </form>

      </div>
    </div>
  );

}