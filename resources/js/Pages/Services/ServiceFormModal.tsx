import React, { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import type { Services, ServiceFormData } from './types';
import styles from './Modal.module.css';

interface Props {
  mode: 'add' | 'edit';
  service?: Services;
  storeUrl: string;
  onClose: () => void;
}

export default function ServiceFormModal({ mode, service, storeUrl, onClose }: Props) {
  const isEdit = mode === 'edit';

  const { data, setData, post, put, processing, errors, reset } =
    useForm<ServiceFormData>({
      name: service?.name ?? '',
      description: service?.description ?? '',
      price: service?.price ?? '',
      is_active: service?.is_active ?? true,
    });

  function handleSubmit(e: FormEvent) {
    e.preventDefault();

    const opts = { onSuccess: () => { reset(); onClose(); } };

    if (isEdit) {
      put(storeUrl, opts);
    } else {
      post(storeUrl, opts);
    }
  }

  return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>

        <div className={styles.modalHeader}>
          <h2>{isEdit ? 'Edit Service' : 'Add New Service'}</h2>
          <button className={styles.closeBtn} onClick={onClose}>✕</button>
        </div>

        <form onSubmit={handleSubmit}>
          <div className={styles.modalBody}>

            <div className={styles.formGroup}>
              <label>Name</label>
              <input
                type="text"
                value={data.name}
                onChange={(e) => setData('name', e.target.value)}
                placeholder="Service name"
                className={errors.name ? styles.inputError : styles.input}
              />
              {errors.name && <span className={styles.errorMsg}>{errors.name}</span>}
            </div>

            <div className={styles.formGroup}>
              <label>Description</label>
              <textarea
                value={data.description}
                onChange={(e) => setData('description', e.target.value)}
                placeholder="Service description"
                rows={3}
                className={errors.description ? styles.inputError : styles.input}
              />
              {errors.description && <span className={styles.errorMsg}>{errors.description}</span>}
            </div>

            <div className={styles.formGroup}>
              <label>Price</label>
              <input
                type="number"
                value={data.price}
                onChange={(e) => setData('price', e.target.value)}
                placeholder="0.00"
                step="0.01"
                min="0"
                className={errors.price ? styles.inputError : styles.input}
              />
              {errors.price && <span className={styles.errorMsg}>{errors.price}</span>}
            </div>

            <div className={styles.formGroup}>
              <label>
                <input
                  type="checkbox"
                  checked={data.is_active}
                  onChange={(e) => setData('is_active', e.target.checked)}
                />
                Active
              </label>
            </div>

          </div>

          <div className={styles.modalFooter}>
            <button type="button" className={styles.cancelBtn} onClick={onClose}>Cancel</button>
            <button type="submit" className={styles.submitBtn} disabled={processing}>
              {processing ? 'Saving...' : isEdit ? 'Save Changes' : 'Add Service'}
            </button>
          </div>
        </form>

      </div>
    </div>
  );
}
