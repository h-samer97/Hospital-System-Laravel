import { useForm } from "@inertiajs/react";
import { FormEvent, type FC } from "react";
import { InsuranceFormData, insurances } from "./types";
import styles from "./InsuranceFormModal.module.css";

interface Props {
  mode: "edit" | "add";
  insurance?: insurances | null;
  url_store: string;
  onClose: () => void;
}
const FormModal: FC<Props> = ({ mode, insurance, onClose, url_store }) => {

  const isEdit = mode === "edit";

  const { data, setData, reset, processing, errors, post } = useForm<InsuranceFormData>(
    {
      name: insurance?.name ?? '',
      note: insurance?.note ?? '',
      insurance_code: insurance?.insurance_code ?? '',
      discount_percentage: insurance?.discount_percentage ?? '',
      company_rate: insurance?.company_rate ?? '',
      is_active: insurance?.is_active ?? true,
    }

  );

  const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    const opts = {
      onSuccess: () => {
        reset();
        onClose();
      },
    };

    isEdit
      ? post(url_store + "?_method=PUT", opts)
      : post(url_store, opts);
  };

  return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>

        <div className={styles.header}>
          <h2>{isEdit ? 'Edit Insurance' : 'Add New Insurance'}</h2>
          <button className={styles.closeBtn} onClick={onClose}>✕</button>
        </div>

        <form onSubmit={handleSubmit}>
          <div className={styles.body}>

            <div className={styles.formGroup}>
              <label>Insurance Name</label>
              <input
                type="text"
                className={errors.name ? styles.inputError : styles.input}
                placeholder="e.g. Golden Care"
                value={data.name}
                onChange={(e) => setData('name', e.target.value)}
                autoFocus
              />
              {errors.name && <span className={styles.errorMsg}>{errors.name}</span>}
            </div>

            <div className={styles.formGroup}>
              <label>Insurance Code</label>
              <input
                type="text"
                value={data.insurance_code}
                onChange={(e) => setData('insurance_code', e.target.value)}
                className={errors.insurance_code ? styles.inputError : styles.input}
                placeholder="e.g. INS-001"
              />
              {errors.insurance_code && <span className={styles.errorMsg}>{errors.insurance_code}</span>}
            </div>

            <div className={styles.row}>
              <div className={styles.formGroup}>
                <label>Discount %</label>
                <input
                  type="number"
                  value={data.discount_percentage}
                  onChange={(e) => setData('discount_percentage', e.target.value)}
                  className={errors.discount_percentage ? styles.inputError : styles.input}
                  min="0" max="100" step="0.01"
                  placeholder="e.g. 20"
                />
                {errors.discount_percentage && <span className={styles.errorMsg}>{errors.discount_percentage}</span>}
              </div>
              <div className={styles.formGroup}>
                <label>Company Rate %</label>
                <input
                  type="number"
                  value={data.company_rate}
                  onChange={(e) => setData('company_rate', e.target.value)}
                  className={errors.company_rate ? styles.inputError : styles.input}
                  min="0" max="100" step="0.01"
                  placeholder="e.g. 80"
                />
                {errors.company_rate && <span className={styles.errorMsg}>{errors.company_rate}</span>}
              </div>
            </div>

            <div className={styles.formGroup}>
              <label>Notes <small>(optional)</small></label>
              <textarea
                value={data.note}
                onChange={(e) => setData('note', e.target.value)}
                className={styles.input}
                placeholder="Any additional notes..."
                rows={3}
              />
            </div>

            <div className={styles.formGroup}>
              <label>Status</label>
              <select className={styles.input} value={data.is_active ? '1' : '0'}
                onChange={(e) => setData('is_active', e.target.value === '1')}>
                <option value="1">✅ Active</option>
                <option value="0">❌ Inactive</option>
              </select>
            </div>

          </div>

          <div className={styles.footer}>
            <button type="button" className={styles.cancelBtn} onClick={onClose}>
              Cancel
            </button>
            <button type="submit" className={styles.submitBtn} disabled={processing}>
              {processing ? 'Saving...' : isEdit ? 'Save Changes' : 'Add Insurance'}
            </button>
          </div>
        </form>

      </div>
    </div>
  );
}
export default FormModal;