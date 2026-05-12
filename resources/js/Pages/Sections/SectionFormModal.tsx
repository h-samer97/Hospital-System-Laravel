import React, { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import type { Section, SectionForm } from './types';
import styles from './Modal.module.css';

interface Props {
  mode: 'add' | 'edit';
  section?: Section;
  onClose: () => void;
}

export default function SectionFormModal({ mode, section, onClose }: Props) {
  const isEdit = mode === 'edit';

  const { data, setData, post, put, processing, errors, reset } =
    useForm<SectionForm>({
      name: section?.name ?? '',
      is_active: section?.is_active ?? true,
    });

  function handleSubmit(e: FormEvent) {
    e.preventDefault();

    if (isEdit && section) {
      // PUT /Sections/{id}
      put(route('sections.update', section.id), {
        onSuccess: () => { reset(); onClose(); },
      });
    } else {
      // POST /Sections
      post(route('sections.store'), {
        onSuccess: () => { reset(); onClose(); },
      });
    }
  }

  return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>

        <div className={styles.modalHeader}>
          <h2>{isEdit ? 'تعديل القسم' : 'إضافة قسم جديد'}</h2>
          <button className={styles.closeBtn} onClick={onClose}>✕</button>
        </div>

        <form onSubmit={handleSubmit}>
          <div className={styles.modalBody}>

            <div className={styles.formGroup}>
              <label>اسم القسم</label>
              <input
                type="text"
                value={data.name}
                onChange={(e) => setData('name', e.target.value)}
                placeholder="مثال: قسم الطوارئ"
                className={errors.name ? styles.inputError : styles.input}
              />
              {errors.name && (
                <span className={styles.errorMsg}>{errors.name}</span>
              )}
            </div>

            <div className={styles.formGroup}>
              <label>
                <input
                  type="checkbox"
                  checked={data.is_active}
                  onChange={(e) => setData('is_active', e.target.checked)}
                />
                نشط
              </label>
            </div>

          </div>

          <div className={styles.modalFooter}>
            <button
              type="button"
              className={styles.cancelBtn}
              onClick={onClose}
            >
              إلغاء
            </button>
            <button
              type="submit"
              className={styles.submitBtn}
              disabled={processing}
            >
              {processing ? 'جاري الحفظ...' : isEdit ? 'حفظ التعديلات' : 'إضافة'}
            </button>
          </div>
        </form>

      </div>
    </div>
  );
}