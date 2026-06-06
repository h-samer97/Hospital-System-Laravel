import { Patient } from './types';
import PatientFormData from './types';
import styles from './PatientFormModal.module.css';
import { useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { X as CloseIcon } from 'lucide-react';

interface Props {
  mode: 'add' | 'edit';
  patient?: Patient;
  url_store: string;
  onClose: () => void;
}

const PatientFormModal = ({ mode, patient, url_store, onClose }: Props) => {

  const isEdit = mode === 'edit';

  const { data, setData, processing, errors, post, put, reset } = useForm<PatientFormData>({
    name: patient?.name ?? '',
    email: patient?.email ?? '',
    password: '',
    phone: patient?.phone ?? '',
    birth_date: patient?.birth_date ?? '',
    gender: patient?.gender ?? undefined,
    blood_group: patient?.blood_group ?? undefined,
    address: patient?.address ?? '',
    is_active: patient?.is_active ?? true,
  });

  const handleSubmit = (e: FormEvent<HTMLFormElement>) => {

    e.preventDefault();
    const opts = {
      onSuccess: () => {
        reset();
        onClose();
      }
    }

    isEdit ?
      put(url_store, opts) :
      post(url_store, opts);

  }

  const handleTrimInputs = (field: keyof PatientFormData) => {
    return (e: React.FocusEvent<HTMLInputElement>) => {
      setData(field, e.target.value.trim() as any);
    };
  }

  return (
    <>
      <div className={styles.overlay} onClick={onClose}>
        <div className={styles.modal} onClick={(e) => e.stopPropagation()}>

          <div className={styles.header}>
            <h2>Add New Patient</h2>
            <button className={styles.closeBtn} onClick={onClose}>
              <CloseIcon />
            </button>
          </div>

          <form onSubmit={handleSubmit}>
            <div className={styles.body}>

              {/* Full Name */}
              <div className={styles.formGroup}>
                <label>Full Name</label>
                <input
                  type="text"
                  className={styles.input}
                  placeholder="e.g. John Smith"
                // onChange={(e) => }
                />
                {/* Example Error State */}
                {/* <input type="text" className={styles.inputError} />
          <span className={styles.errorMsg}>Error message here</span> */}
              </div>

              {/* Email + Phone Row */}
              <div className={styles.row}>
                <div className={styles.formGroup}>
                  <label>Email</label>
                  <input
                    type="email"
                    className={styles.input}
                    placeholder="patient@email.com"
                  />
                </div>
                <div className={styles.formGroup}>
                  <label>Phone</label>
                  <input
                    type="text"
                    className={styles.input}
                    placeholder="e.g. 01012345678"
                  />
                </div>
              </div>

              {/* Password */}
              <div className={styles.formGroup}>
                <label>
                  Password <small>(leave blank to keep current)</small>
                </label>
                <input
                  type="password"
                  className={styles.input}
                  placeholder="Min 8 characters"
                />
              </div>

              {/* Date of Birth + Gender Row */}
              <div className={styles.row}>
                <div className={styles.formGroup}>
                  <label>Date of Birth</label>
                  <input
                    type="date"
                    max="2026-06-04"
                    className={styles.input}
                  />
                </div>
                <div className={styles.formGroup}>
                  <label>Gender</label>
                  <select className={styles.input}>
                    <option value="">-- Choose --</option>
                    <option value="male">👨 Male</option>
                    <option value="female">👩 Female</option>
                  </select>
                </div>
              </div>

              {/* Blood Group + Status Row */}
              <div className={styles.row}>
                <div className={styles.formGroup}>
                  <label>Blood Group</label>
                  <select className={styles.input}>
                    <option value="">-- Choose --</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                  </select>
                </div>
                <div className={styles.formGroup}>
                  <label>Status</label>
                  <select className={styles.input}>
                    <option value="1">✅ Active</option>
                    <option value="0">❌ Inactive</option>
                  </select>
                </div>
              </div>

              {/* Address */}
              <div className={styles.formGroup}>
                <label>Address <small>(optional)</small></label>
                <input
                  type="text"
                  className={styles.input}
                  placeholder="Full address..."
                />
              </div>

            </div>

            <div className={styles.footer}>
              <button type="button" className={styles.cancelBtn} onClick={onClose}>
                Cancel
              </button>
              <button type="submit" className={styles.submitBtn}>
                {mode === 'add' ? 'Add Patient' : 'Save Changes'}
              </button>
            </div>
          </form>

        </div>
      </div>
    </>
  );
};

export default PatientFormModal;