import { useForm } from "@inertiajs/react";
import { Patient } from "../Patients/types";
import { Payment, PaymentFormData } from "./types";
import { useDirtyState } from "@/hooks/useDirtyState";
import React, { FormEvent, useCallback, useMemo, useRef, useState } from "react";
import { useDebounce } from "@/hooks/useDebounce";
import useBinarySearch from "@/hooks/BinarySearch";
import { sanitize, sanitizeAndAmount, sanitizeAndTrim } from "@/utils/santizie";
import styles from "./PaymentFormModal.module.css";

interface Props {
  mode: 'add' | 'edit';
  payment?: Payment;
  patients: Pick<Patient, 'name' | 'id'>[];
  url_store: string;
  onClose: () => void;
}
export default function PaymentFormModal({ mode, payment, patients, url_store, onClose }: Props) {

  const isEdit = mode === 'edit';
  const { data, setData, processing, errors, put, post, reset, isDirty } = useForm<PaymentFormData>({
    patient_id: payment?.patient_id ?? '',
    amount: payment?.amount ?? '',
    description: payment?.description ?? '',
  });

  const handleClose = useDirtyState(isDirty, onClose);
  const [patientSearch, setPatientSearch] = useState('');
  const debouncedPatientSearch = useDebounce(patientSearch, 300);
  const binarySearch = useBinarySearch(patients, (p) => p.name);
  const filterdPatients = useMemo(
    () => binarySearch(debouncedPatientSearch),
    [binarySearch, debouncedPatientSearch]
  );
  const selectPatient = patients.find(p => p.id === Number(data.patient_id));
  const handleDescriptionBlur = useCallback(
    (e: React.FocusEvent<HTMLTextAreaElement>) => {
      setData('description', sanitizeAndTrim(e.target.value));
    },
    [setData]
  );

  const handleAmountChange = useCallback(
    (e: React.ChangeEvent<HTMLInputElement>) => {
      setData('amount', sanitizeAndAmount(e.target.value));
    }, [setData]
  );
  const handlePatientSelect = useCallback(
    (e: React.ChangeEvent<HTMLSelectElement>) => {
      setData('patient_id', Number(e.target.value) || '');
      setPatientSearch('');
    }, [setData]
  );
  const handleSubmet = useCallback(
    (e: FormEvent) => {
      e.preventDefault();

      const opts = {
        onSuccess: () => {
          reset();
          onClose();
        },
      };

      if (isEdit) {
        put(url_store, opts);
      } else {
        post(url_store, opts);
      }
    },
    [isEdit, onClose, post, put, reset, url_store]
  );

  const amountNum = Number(data.amount) || 0;
  const bodyRef = useRef<HTMLDivElement | null>(null);

  const scrollIntoView = useCallback((el: HTMLElement | null) => {
    if (!el) return;
    // scroll the nearest scrollable ancestor into view (overlay or modal body)
    el.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
  }, []);

  return (
    <div className={styles.overlay}>
      <div className={styles.modal}>

        {/* Header */}
        <div className={styles.header}>
          <div>
            <h2>{isEdit ? 'Edit Payment' : 'New Payment'}</h2>
            <p className={styles.subtitle}>سند صرف</p>
          </div>
          <div className={styles.headerRight}>
            {isDirty && (
              <span className={styles.dirtyBadge} title="Unsaved changes">
                ● Unsaved
              </span>
            )}
            <button className={styles.closeBtn} aria-label="Close" type="button" onClick={handleClose}>✕</button>
          </div>
        </div>

        <form onSubmit={handleSubmet}>
          <div ref={bodyRef} className={styles.body}>

            {/* ===== Patient Search + Select Group ===== */}
            <div className={styles.formGroup}>
              <label htmlFor="patient-search">Patient</label>

              <input
                id="patient-search"
                type="text"
                placeholder="Type to search patients..."
                className={styles.searchInput}
                autoComplete="off"
                value={patientSearch}
                onChange={(e) => setPatientSearch(e.target.value)}
                onFocus={(e) => scrollIntoView(e.currentTarget)}
              />

              <select
                className={styles.input}
                size={4}
                value={data.patient_id ? String(data.patient_id) : ''}
                onChange={handlePatientSelect}
                aria-label="Select patient"
                onFocus={(e) => scrollIntoView(e.currentTarget)}
              >
                <option value="">— Select Patient —</option>
                {filterdPatients.map((p) => (
                  <option key={p.id} value={String(p.id)}>
                    {p.name}
                  </option>
                ))}
              </select>

              {selectPatient && (
                <div className={styles.selectedTag}>
                  <span className={styles.selectedDot} />
                  {selectPatient.name}
                  <button
                    type="button"
                    className={styles.clearBtn}
                    onClick={() => setData('patient_id', '')}
                    aria-label="Clear patient selection"
                  >
                    ✕
                  </button>
                </div>
              )}

              <span className={styles.searchCount}>
                {filterdPatients.length} of {patients.length} patients
              </span>

              {errors.patient_id && <span className={styles.errorMsg}>{errors.patient_id}</span>}
            </div>

            {/* ===== Amount Group ===== */}
            <div className={styles.formGroup}>
              <label htmlFor="amount">Amount (EGP)</label>
              <div className={styles.amountWrapper}>
                <span className={styles.currencyPrefix}>EGP</span>
                <input
                  id="amount"
                  type="number"
                  className={styles.amountInput}
                  placeholder="0.00"
                  min="0.01"
                  step="0.01"
                  value={data.amount}
                  onChange={handleAmountChange}
                  onFocus={(e) => scrollIntoView(e.currentTarget)}
                />
              </div>

              <span className={styles.amountPreview}>
                {new Intl.NumberFormat('en-US', { style: 'currency', currency: 'EGP' }).format(amountNum)}
              </span>

              {errors.amount && <span className={styles.errorMsg}>{errors.amount}</span>}
            </div>

            {/* ===== Description Group ===== */}
            <div className={styles.formGroup}>
              <label htmlFor="description">Description</label>
              <textarea
                id="description"
                className={styles.input}
                placeholder="Payment description..."
                rows={3}
                maxLength={500}
                value={data.description}
                onChange={(e) => setData('description', e.target.value)}
                onBlur={handleDescriptionBlur}
                onFocus={(e) => scrollIntoView(e.currentTarget)}
              />
              <div className={styles.descFooter}>
                <span className={styles.charCount}>
                  {(data.description || '').length} / 500
                </span>
              </div>

              {errors.description && <span className={styles.errorMsg}>{errors.description}</span>}
            </div>

            {/* ===== Accounting Ledger Entries Preview Card ===== */}
            <div className={styles.accountingPreview}>
              <p className={styles.previewTitle}>📊 Accounting Entries Preview</p>
              <div className={styles.previewGrid}>
                <div className={styles.previewItem}>
                  <span className={styles.previewLabel}>Fund Account</span>
                  <span className={styles.previewSub}>Disbursement</span>
                  <span className={styles.creditTag}>− {new Intl.NumberFormat('en-US', { style: 'currency', currency: 'EGP' }).format(amountNum)}</span>
                </div>

                <div className={styles.previewDivider}>⇄</div>

                <div className={styles.previewItem}>
                  <span className={styles.previewLabel}>Patient Account</span>
                  <span className={styles.previewSub}>Debt Increase</span>
                  <span className={styles.debitTag}>+ {new Intl.NumberFormat('en-US', { style: 'currency', currency: 'EGP' }).format(amountNum)}</span>
                </div>
              </div>
              <p className={styles.previewNote}>عكس سند القبض — الصندوق يصرف، دين المريض يزيد</p>
            </div>

          </div>

          {/* Footer Controls */}
          <div className={styles.footer}>
            <button
              type="button"
              className={styles.cancelBtn}
              onClick={() => {
                reset();
                onClose();
              }}
            >
              Discard Changes
            </button>
            <button type="submit" className={styles.submitBtn} disabled={processing} aria-disabled={processing}>
              {processing ? 'Saving...' : '✔ Save Payment'}
            </button>
          </div>
        </form>

      </div>
    </div>
  );

}