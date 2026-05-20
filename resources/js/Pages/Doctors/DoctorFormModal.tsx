import React, { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';
import type { Doctor, Section, DoctorFormData, DoctorSection, Appointment } from './types';
import styles from './Modal.module.css';

interface Props {
  mode: 'add' | 'edit';
  doctor?: Doctor;
  sections: DoctorSection[];
  appointments: Appointment[];
  storeUrl: string;
  onClose: () => void;
}

export default function DoctorFormModal({ mode, doctor, sections, appointments, storeUrl, onClose }: Props) {
  const isEdit = mode === 'edit';

  // useForm manages state, errors, and submit
  const { data, setData, post, put, processing, errors, reset } =
    useForm<DoctorFormData>({
      section_id:      doctor?.section?.id ?? '',
      name:            doctor?.name ?? '',
      appointment_ids: doctor?.appointments?.map(a => a.id) ?? [],
      email:           doctor?.email ?? '',
      password:        '',
      phone:           doctor?.phone ?? '',
      price:           String(doctor?.price ?? ''),
      image:           null,
    });

  function handleSubmit(e: FormEvent) {
    e.preventDefault();

    const opts = { onSuccess: () => { reset(); onClose(); } };

    // Inertia accepts URL directly — without Ziggy
    if (isEdit) {
      // PUT — but with file upload we use post + method spoofing
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
              <label>Appointment Days</label>
              <div className={styles.checkboxGroup}>
                {appointments.map(appointment => (
                  <label key={appointment.id} className={styles.checkboxLabel}>
                    <input
                      type="checkbox"
                      value={appointment.id}
                      checked={data.appointment_ids.includes(appointment.id)}
                      onChange={(e) => {
                        if (e.target.checked) {
                          setData('appointment_ids', [...data.appointment_ids, Number(e.target.value)]);
                        } else {
                          setData('appointment_ids', data.appointment_ids.filter(id => id !== Number(e.target.value)));
                        }
                      }}
                    />
                    {appointment.name}
                  </label>
                ))}
              </div>
              {errors.appointment_ids && <span className={styles.errorMsg}>{errors.appointment_ids}</span>}
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