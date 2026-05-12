import React from 'react';
import { useForm } from '@inertiajs/react';
import type { Section } from './types';
import styles from './Modal.module.css';

interface Props {
  section: Section;
  onClose: () => void;
}

export default function DeleteModal({ section, onClose }: Props) {
  const { delete: destroy, processing } = useForm();

  function handleDelete() {
    // DELETE /Sections/{id}
    destroy(route('sections.destroy', section.id), {
      onSuccess: onClose,
    });
  }

  return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={`${styles.modal} ${styles.deleteModal}`} onClick={(e) => e.stopPropagation()}>

        <div className={styles.modalHeader}>
          <h2>تأكيد الحذف</h2>
          <button className={styles.closeBtn} onClick={onClose}>✕</button>
        </div>

        <div className={styles.modalBody}>
          <p className={styles.deleteWarning}>
            هل أنت متأكد من حذف قسم <strong>"{section.name}"</strong>؟
          </p>
          <p className={styles.deleteNote}>
            ⚠️ لا يمكن التراجع عن هذا الإجراء.
          </p>
        </div>

        <div className={styles.modalFooter}>
          <button className={styles.cancelBtn} onClick={onClose}>
            إلغاء
          </button>
          <button
            className={styles.dangerBtn}
            onClick={handleDelete}
            disabled={processing}
          >
            {processing ? 'جاري الحذف...' : 'نعم، احذف'}
          </button>
        </div>

      </div>
    </div>
  );
}