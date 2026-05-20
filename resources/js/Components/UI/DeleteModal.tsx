import React from 'react';
import { useForm } from '@inertiajs/react';
import styles from '@/Pages/Doctors/Modal.module.css';

interface Props {
  name: string;
  deleteUrl: string;
  onClose: () => void;
}

export default function DeleteModal({ name, deleteUrl, onClose }: Props) {
  const { delete: destroy, processing } = useForm();

  return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={`${styles.modal} ${styles.deleteModal}`} onClick={(e) => e.stopPropagation()}>
        <div className={styles.modalHeader}>
          <h2>Confirm Delete</h2>
          <button className={styles.closeBtn} onClick={onClose}>✕</button>
        </div>
        <div className={styles.modalBody}>
          <p>Are you sure you want to delete <strong>"{name}"</strong>?</p>
          <p className={styles.deleteNote}>⚠️ This action cannot be undone.</p>
        </div>
        <div className={styles.modalFooter}>
          <button className={styles.cancelBtn} onClick={onClose}>Cancel</button>
          <button className={styles.dangerBtn} disabled={processing}
            onClick={() => destroy(deleteUrl, { onSuccess: onClose })}>
            {processing ? 'Deleting...' : 'Yes, Delete'}
          </button>
        </div>
      </div>
    </div>
  );
}