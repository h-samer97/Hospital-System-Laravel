import { Patient } from './types';
import PatientFormData from './types';
import styles from './PatientFormModal.module.css';
import { useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { X as CloseIcon } from 'lucide-react';

interface Props {
  mode: 'add' | 'edit';
  patient?: Patient;
  storeUrl: string;
  onClose: () => void;
}

const PatientFormModal = ({ mode, patient, storeUrl, onClose }: Props) => {

  const isEdit = mode === 'edit';

  const { data, setData, processing, errors, post, reset } = useForm<PatientFormData>({
    name: patient?.name ?? '',
    email: patient?.email ?? '',
    password: '',
    phone: patient?.phone ?? '',
    birth_date: patient?.birth_date ?? '',
    gender: patient?.gender ?? undefined,
    blood_group: patient?.blood_group ?? undefined,
    address: patient?.address ?? '',
  });

  const handleSubmit = (e: FormEvent<HTMLFormElement>) => {

    e.preventDefault();
    const opts = {
      onSuccess: () => {
        reset();
        onClose();
      }
    }

    if (isEdit) {
      post(`${storeUrl}?_method=PUT`, opts);
    } else {
      post(storeUrl, opts);
    }

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
            <h2>{isEdit ? 'Edit Patient' : 'Add New Patient'}</h2>
            <button className={styles.closeBtn} onClick={onClose} type="button">
              <CloseIcon />
            </button>
          </div>

          <form onSubmit={handleSubmit} action={storeUrl} method="post">
            <div className={styles.body}>

              {/* Full Name */}
              <div className={styles.formGroup}>
                <label>Full Name</label>
                <input
                  type="text"
                  className={errors.name ? styles.inputError : styles.input}
                  placeholder="e.g. John Smith"
                  onChange={(e) => setData('name', e.target.value)}
                  onBlur={handleTrimInputs('name')}
                  value={data.name}
                  autoFocus
                />

                {errors.name && (
                  <span className={styles.errorMsg}>{errors.name}</span>
                )}
              </div>

              {/* Email + Phone Row */}
              <div className={styles.row}>
                <div className={styles.formGroup}>
                  <label>Email</label>
                  <input
                    type="email"
                    className={styles.input}
                    placeholder="patient@email.com"
                    onChange={(e) => setData('email', e.target.value)}
                    onBlur={handleTrimInputs('email')}
                    value={data.email}
                  />
                  {errors.email && (
                    <span className={styles.errorMsg}>{errors.email}</span>
                  )}

                </div>
                <div className={styles.formGroup}>
                  <label>Phone</label>
                  <input
                    type="text"
                    className={styles.input}
                    placeholder="e.g. 01012345678"
                    onChange={(e) => {

                      const val = e.target.value.replace(/[^0-9+\-\s]/g, ''); // Reverse the condition to allow only numbers, +, -, and spaces

                      setData('phone', val);
                    }}
                    onBlur={handleTrimInputs('phone')}
                    value={data.phone}
                  />
                  {errors.phone && (
                    <span className={styles.errorMsg}>{errors.phone}</span>
                  )}
                </div>
              </div>

              {/* Password */}
              <div className={styles.formGroup}>
                <label>
                  Password  {isEdit && <small> (leave blank to keep current)</small>}
                </label>
                <input
                  type="password"
                  className={styles.input}
                  placeholder={isEdit ? '••••••••' : 'Enter password'}
                  onChange={(e) => setData('password', e.target.value)}
                  onBlur={handleTrimInputs('password')}
                  value={data.password}
                />
                {errors.password && (
                  <span className={styles.errorMsg}>{errors.password}</span>
                )}
              </div>

              {/* Date of Birth + Gender Row */}
              <div className={styles.row}>
                <div className={styles.formGroup}>
                  <label>Date of Birth</label>
                  <input
                    type="date"
                    max="2026-06-04"
                    className={styles.input}
                    onChange={(e) => setData('birth_date', e.target.value)}
                    value={data.birth_date}
                  />
                  {errors.birth_date && (
                    <span className={styles.errorMsg}>{errors.birth_date}</span>
                  )}
                </div>

                <div className={styles.formGroup}>
                  <label>Gender</label>
                  <select
                    className={styles.input}
                    onChange={(e) => setData('gender', e.target.value as PatientFormData['gender'])}
                    value={data.gender ?? ''}
                  >
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
                  <select
                    className={styles.input}
                    onChange={(e) => setData('blood_group', e.target.value as PatientFormData['blood_group'])}
                    value={data.blood_group ?? ''}
                  >
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
              </div>

              {/* Address */}
              <div className={styles.formGroup}>
                <label>Address</label>
                <input
                  type="text"
                  className={styles.input}
                  placeholder="Full address..."
                  onChange={(e) => setData('address', e.target.value)}
                  onBlur={handleTrimInputs('address')}
                  value={data.address}
                  required
                />
              </div>

            </div>

            <div className={styles.footer}>
              <button type="button" className={styles.cancelBtn} onClick={onClose}>
                Cancel
              </button>
              <button type="submit" className={styles.submitBtn} disabled={processing}>
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