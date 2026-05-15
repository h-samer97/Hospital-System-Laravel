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
          <h2>تأكيد الحذف</h2>
          <button className={styles.closeBtn} onClick={onClose}>✕</button>
        </div>
        <div className={styles.modalBody}>
          <p>هل أنت متأكد من حذف <strong>"{name}"</strong>؟</p>
          <p className={styles.deleteNote}>⚠️ لا يمكن التراجع عن هذا الإجراء.</p>
        </div>
        <div className={styles.modalFooter}>
          <button className={styles.cancelBtn} onClick={onClose}>إلغاء</button>
          <button className={styles.dangerBtn} disabled={processing}
            onClick={() => destroy(deleteUrl, { onSuccess: onClose })}>
            {processing ? 'جاري الحذف...' : 'نعم، احذف'}
          </button>
        </div>
      </div>
    </div>
  );
}