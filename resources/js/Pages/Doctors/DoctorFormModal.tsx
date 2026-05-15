import React, { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import type { Doctor, Section, DoctorFormData, DoctorSection } from './types';
import styles from './Modal.module.css';

const DAYS = ['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday'];

interface Props {
  mode: 'add' | 'edit';
  doctor?: Doctor;
  sections: DoctorSection[];
  storeUrl: string;
  onClose: () => void;
}

export default function DoctorFormModal({ mode, doctor, sections, storeUrl, onClose }: Props) {
  const isEdit = mode === 'edit';

  // useForm يدير الـ state والـ errors والـ submit
  const { data, setData, post, put, processing, errors, reset } =
    useForm<DoctorFormData>({
      section_id:      doctor?.section?.id ?? '',
      name:            doctor?.name ?? '',
      appointments:    doctor?.appointments ?? '',
      email:           doctor?.email ?? '',
      password:        '',
      phone:           doctor?.phone ?? '',
      price:           String(doctor?.price ?? ''),
      image:           null,
    });

  function handleSubmit(e: FormEvent) {
    e.preventDefault();

    const opts = { onSuccess: () => { reset(); onClose(); } };

    // Inertia يقبل URL مباشرة — بدون Ziggy
    if (isEdit) {
      // PUT — لكن مع رفع ملف نستخدم post + method spoofing
      post(storeUrl + '?_method=PUT', opts);
    } else {
      post(storeUrl, opts);
    }
  }

  return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>

        <div className={styles.modalHeader}>
          <h2>{isEdit ? 'Edit Doctor' : 'Add New Doctor'}</h2>
          <button className={styles.closeBtn} onClick={onClose}>✕</button>
        </div>

        <form onSubmit={handleSubmit} encType="multipart/form-data">
          <div className={styles.modalBody}>

            {/* Section */}
            <div className={styles.formGroup}>
              <label>Section</label>
              <select
                value={data.section_id}
                onChange={(e) => setData('section_id', Number(e.target.value))}
                className={errors.section_id ? styles.inputError : styles.input}
              >
                <option value="">-- Select Section --</option>
                {sections.map(s => (
                  <option key={s.id} value={s.id}>{s.name}</option>
                ))}
              </select>
              {errors.section_id && <span className={styles.errorMsg}>{errors.section_id}</span>}
            </div>

            {/* Name */}
            <div className={styles.formGroup}>
              <label>Name</label>
              <input type="text" value={data.name}
                onChange={(e) => setData('name', e.target.value)}
                className={errors.name ? styles.inputError : styles.input}
                placeholder="Dr. Ahmed Mohamed" />
              {errors.name && <span className={styles.errorMsg}>{errors.name}</span>}
            </div>

            {/* Appointments */}
            <div className={styles.formGroup}>
              <label>Appointment Day</label>
              <select value={data.appointments}
                onChange={(e) => setData('appointments', e.target.value)}
                className={styles.input}>
                <option value="">-- Select Day --</option>
                {DAYS.map(d => <option key={d} value={d}>{d}</option>)}
              </select>
              {errors.appointments && <span className={styles.errorMsg}>{errors.appointments}</span>}
            </div>

            {/* Email and Phone */}
            <div className={styles.row}>
              <div className={styles.formGroup}>
                <label>Email</label>
                <input type="email" value={data.email}
                  onChange={(e) => setData('email', e.target.value)}
                  className={errors.email ? styles.inputError : styles.input} />
                {errors.email && <span className={styles.errorMsg}>{errors.email}</span>}
              </div>
              <div className={styles.formGroup}>
                <label>Phone</label>
                <input type="text" value={data.phone}
                  onChange={(e) => setData('phone', e.target.value)}
                  className={errors.phone ? styles.inputError : styles.input} />
                {errors.phone && <span className={styles.errorMsg}>{errors.phone}</span>}
              </div>
            </div>

            {/* Price and Password */}
            <div className={styles.row}>
              <div className={styles.formGroup}>
                <label>Consultation Price (EGP)</label>
                <input type="number" value={data.price}
                  onChange={(e) => setData('price', e.target.value)}
                  className={errors.price ? styles.inputError : styles.input}
                  min="0" step="50" />
                {errors.price && <span className={styles.errorMsg}>{errors.price}</span>}
              </div>
              <div className={styles.formGroup}>
                <label>Password {isEdit && <small>(leave empty to keep current)</small>}</label>
                <input type="password" value={data.password}
                  onChange={(e) => setData('password', e.target.value)}
                  className={errors.password ? styles.inputError : styles.input}
                  placeholder={isEdit ? '••••••••' : ''} />
                {errors.password && <span className={styles.errorMsg}>{errors.password}</span>}
              </div>
            </div>

            {/* Upload Image */}
            <div className={styles.formGroup}>
              <label>Doctor Image</label>
              {doctor?.image_url && (
                <img src={doctor.image_url} alt="current" className={styles.previewImg} />
              )}
              <input type="file" accept="image/*"
                onChange={(e) => setData('image', e.target.files?.[0] ?? null)}
                className={styles.fileInput} />
              {errors.image && <span className={styles.errorMsg}>{errors.image}</span>}
            </div>

          </div>

          <div className={styles.modalFooter}>
            <button type="button" className={styles.cancelBtn} onClick={onClose}>Cancel</button>
            <button type="submit" className={styles.submitBtn} disabled={processing}>
              {processing ? 'Saving...' : isEdit ? 'Save Changes' : 'Add Doctor'}
            </button>
          </div>
        </form>

      </div>
    </div>
  );
}