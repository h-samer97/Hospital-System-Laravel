import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Toast from '@/Components/UI/Toast';
import PatientFormModal from './PatientFormModal';
import DeleteModal from '@/Components/UI/DeleteModal';
import { Patient } from './types';
import { timeAgo } from '@/utils/date';
import styles from './Index.module.css';

interface ModalState {
  mode: 'add' | 'edit';
  patient: Patient | null;
}

interface Props {
  patients: Patient[];
  url_store: string;
}

export default function PatientsIndex({ patients, url_store }: Props) {
  const [modal, setModal] = useState<ModalState | null>(null);
  const [deletePatient, setDeletePatient] = useState<Patient | null>(null);
  const [search, setSearch] = useState('');

  const filtered = patients.filter(p =>
    p.name.toLowerCase().includes(search.toLowerCase()) ||
    p.email.toLowerCase().includes(search.toLowerCase()) ||
    p.phone.includes(search)
  );

  return (
    <DashboardLayout title='patients Managment'>
      <Head title="Patients" />
      <Toast />

      <div className={styles.page}>
        <div className={styles.header}>
          <div>
            <h1>Patients</h1>
            <p className={styles.breadcrumb}>Dashboard / Patients</p>
          </div>
          <button className={styles.addBtn}
            onClick={() => setModal({ mode: 'add', patient: null })}>
            + Add Patient
          </button>
        </div>

        <div className={styles.card}>
          <div className={styles.toolbar}>
            <input type="text"
              placeholder="Search by name, email or phone..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className={styles.searchInput} />
            <span className={styles.count}>{filtered.length} patients</span>
          </div>

          <div className={styles.tableWrapper}>
            <table className={styles.table}>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Date of Birth</th>
                  <th>Age</th>
                  <th>Gender</th>
                  <th>Blood Group</th>
                  <th>Address</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {filtered.length === 0 ? (
                  <tr>
                    <td colSpan={11} className={styles.empty}>No patients found</td>
                  </tr>
                ) : (
                  filtered.map((patient, i) => (
                    <tr key={patient.id}>
                      <td>{i + 1}</td>
                      <td>
                        <div className={styles.nameCell}>
                          <span className={styles.avatar}>
                            {patient.name.charAt(0).toUpperCase()}
                          </span>
                          {patient.name}
                        </div>
                      </td>
                      <td>{patient.email}</td>
                      <td>{patient.phone}</td>
                      <td>{patient.birth_date}</td>
                      <td>{patient.age} yrs</td>
                      <td>
                        <span className={patient.gender === 'male'
                          ? styles.badgeMale : styles.badgeFemale}>
                          {patient.gender_label}
                        </span>
                      </td>
                      <td>
                        <span className={styles.bloodBadge}>
                          {patient.blood_group}
                        </span>
                      </td>
                      <td className={styles.address}>
                        {patient.address?.slice(0, 30) ?? '—'}
                      </td>
                      <td>
                        <span className={patient.is_active
                          ? styles.badgeActive : styles.badgeInactive}>
                          <span className={styles.dot} />
                          {patient.is_active ? 'Active' : 'Inactive'}
                        </span>
                      </td>
                      <td className={styles.actions}>
                        <button className={styles.editBtn}
                          onClick={() => setModal({ mode: 'edit', patient })}>
                          ✏️
                        </button>
                        <button className={styles.deleteBtn}
                          onClick={() => setDeletePatient(patient)}>
                          🗑️
                        </button>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {modal && (
        <PatientFormModal
          mode={modal.mode}
          patient={modal.patient ?? undefined}
          storeUrl={
            modal.mode === 'add'
              ? url_store
              : modal.patient!.urls.update
          }
          onClose={() => setModal(null)}
        />
      )}

      {deletePatient && (
        <DeleteModal
          name={deletePatient.name}
          deleteUrl={deletePatient.urls.destroy}
          onClose={() => setDeletePatient(null)}
        />
      )}

    </DashboardLayout>
  );
}