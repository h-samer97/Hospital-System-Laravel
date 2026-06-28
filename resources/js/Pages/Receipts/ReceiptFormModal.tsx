import React, { FormEvent, useState, useMemo, useCallback } from 'react';
import { useForm } from '@inertiajs/react';
import { useBinarySearch } from '@/hooks/BinarySearch';
import type { Receipt, ReceiptFormData } from './types';
import type { Patient } from '@/Pages/Patients/types';
import styles from './ReceiptFormModal.module.css';

interface Props {
  mode: 'add' | 'edit';
  receipt?: Receipt;
  patients: Pick<Patient, 'id' | 'name'>[];
  storeUrl: string;
  onClose: () => void;
}

export default function ReceiptFormModal({
  mode, receipt, patients, storeUrl, onClose
}: Props) {
  const isEdit = mode === 'edit';

  // ✅ Binary Search على قائمة المرضى
  const [patientSearch, setPatientSearch] = useState('');
  const binarySearch = useBinarySearch(patients, p => p.name);
  const filteredPatients = useMemo(
    () => binarySearch(patientSearch),
    [binarySearch, patientSearch]
  );

  const { data, setData, post, put, processing, errors, reset } =
    useForm<ReceiptFormData>({
      patient_id: receipt?.patient_id ?? '',
      debit: receipt?.debit ?? '',
      description: receipt?.description ?? '',
    });

  // ✅ trimOnBlur — تنظيف المسافات الزائدة
  const trimOnBlur = useCallback((field: keyof ReceiptFormData) => {
    return (e: React.FocusEvent<HTMLInputElement | HTMLTextAreaElement>) => {
      setData(field, e.target.value.trim() as any);
    };
  }, [setData]);

  // ✅ تحقق لحظي — الرقم موجب فقط
  const handleDebitChange = useCallback((e: React.ChangeEvent<HTMLInputElement>) => {
    const val = e.target.value;
    // منع القيم السالبة
    if (Number(val) < 0) return;
    setData('debit', val);
  }, [setData]);

  function handleSubmit(e: FormEvent) {
    e.preventDefault();
    const opts = { onSuccess: () => { reset(); onClose(); } };
    isEdit ? put(storeUrl, opts) : post(storeUrl, opts);
  }

  // الاسم المعروض للمريض المختار
  const selectedPatientName = patients.find(
    p => p.id === Number(data.patient_id)
  )?.name ?? '';

  return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>

        <div className={styles.header}>
          <div>
            <h2>{isEdit ? 'Edit Receipt' : 'New Receipt'}</h2>
            <p className={styles.subtitle}>سند قبض</p>
          </div>
          <button className={styles.closeBtn} onClick={onClose}>✕</button>
        </div>

        <form onSubmit={handleSubmit}>
          <div className={styles.body}>

            {/* ===== اختيار المريض مع Binary Search ===== */}
            <div className={styles.formGroup}>
              <label>Patient</label>

              {/* حقل البحث في المرضى */}
              <input type="text"
                placeholder="Search patients..."
                value={patientSearch}
                onChange={(e) => setPatientSearch(e.target.value)}
                className={styles.searchInput}
              />

              <select value={data.patient_id}
                onChange={(e) => {
                  setData('patient_id', Number(e.target.value));
                  setPatientSearch(''); // مسح البحث بعد الاختيار
                }}
                className={errors.patient_id ? styles.inputError : styles.input}
                size={filteredPatients.length > 0 && patientSearch ? 4 : 1}
              >
                <option value="">-- Select Patient --</option>
                {filteredPatients.map(p => (
                  <option key={p.id} value={p.id}>{p.name}</option>
                ))}
              </select>

              {/* إظهار المريض المختار */}
              {selectedPatientName && !patientSearch && (
                <span className={styles.selectedBadge}>
                  ✅ {selectedPatientName}
                </span>
              )}

              {/* عداد النتائج */}
              {patientSearch && (
                <span className={styles.searchCount}>
                  {filteredPatients.length} results
                </span>
              )}

              {errors.patient_id && (
                <span className={styles.errorMsg}>{errors.patient_id}</span>
              )}
            </div>

            {/* ===== المبلغ ===== */}
            <div className={styles.formGroup}>
              <label>Amount (EGP)</label>
              <input type="number"
                value={data.debit}
                onChange={handleDebitChange}
                onBlur={trimOnBlur('debit')}
                className={errors.debit ? styles.inputError : styles.input}
                placeholder="0.00"
                min="0.01"
                step="0.01"
              />
              {errors.debit && <span className={styles.errorMsg}>{errors.debit}</span>}

              {/* معاينة المبلغ فورية */}
              {Number(data.debit) > 0 && (
                <span className={styles.amountPreview}>
                  = {Number(data.debit).toLocaleString('en-US', {
                    style: 'currency', currency: 'EGP'
                  })}
                </span>
              )}
            </div>

            {/* ===== البيان ===== */}
            <div className={styles.formGroup}>
              <label>Description</label>
              <textarea
                value={data.description}
                onChange={(e) => setData('description', e.target.value)}
                onBlur={trimOnBlur('description')}
                className={`${styles.input} ${errors.description ? styles.inputError : ''}`}
                placeholder="Receipt description..."
                rows={3}
              />
              {/* عداد الأحرف */}
              <span className={`${styles.charCount} ${data.description.length > 450 ? styles.charCountWarn : ''
                }`}>
                {data.description.length} / 500
              </span>
              {errors.description && (
                <span className={styles.errorMsg}>{errors.description}</span>
              )}
            </div>

            {/* ===== ملخص المحاسبة ===== */}
            {Number(data.debit) > 0 && (
              <div className={styles.accountingSummary}>
                <p className={styles.summaryTitle}>Accounting Entries Preview</p>
                <div className={styles.summaryRow}>
                  <span>Fund Account (Debit)</span>
                  <strong className={styles.debitColor}>
                    +{Number(data.debit).toLocaleString()} EGP
                  </strong>
                </div>
                <div className={styles.summaryRow}>
                  <span>Patient Account (Credit)</span>
                  <strong className={styles.creditColor}>
                    -{Number(data.debit).toLocaleString()} EGP
                  </strong>
                </div>
              </div>
            )}

          </div>

          <div className={styles.footer}>
            <button type="button" className={styles.cancelBtn} onClick={onClose}>
              Cancel
            </button>
            <button type="submit" className={styles.submitBtn} disabled={processing}>
              {processing ? 'Saving...' : isEdit ? 'Update Receipt' : 'Save Receipt'}
            </button>
          </div>
        </form>

      </div>
    </div>
  );
}