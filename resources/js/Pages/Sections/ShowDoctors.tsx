import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Toast from '@/Components/UI/Toast';
import UpdatePasswordModal from '@/Pages/Doctors/UpdatePasswordModal';
import UpdateStatusModal from '@/Pages/Doctors/UpdateStatusModal';
import DeleteModal from '@/Components/UI/DeleteModal';
import type { Doctor } from '@/types/models';
import { timeAgo } from '@/utils/date';
import styles from './ShowDoctors.module.css';

interface Props {
  section: { id: number; name: string };
  doctors: Doctor[];
}

export default function ShowDoctors({ section, doctors }: Props) {
  const [passwordDoctor, setPasswordDoctor] = useState<Doctor | null>(null);
  const [statusDoctor, setStatusDoctor]     = useState<Doctor | null>(null);
  const [deleteDoctor, setDeleteDoctor]     = useState<Doctor | null>(null);
  const [openDropdown, setOpenDropdown]     = useState<number | null>(null);

  return (
    <DashboardLayout title={`${section.name} — Doctors`}>
      <Head title={`${section.name} — Doctors`} />
      <Toast />

      <div className={styles.page}>
        <div className={styles.header}>
          <div>
            <h1>{section.name}</h1>
            <p className={styles.breadcrumb}>
              <Link href="/Sections">Sections</Link> / Doctors
            </p>
          </div>
        </div>

        <div className={styles.card}>
          <div className={styles.tableWrapper}>
            <table className={styles.table}>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Photo</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Appointments</th>
                  <th>Status</th>
                  <th>Added</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {doctors.length === 0 ? (
                  <tr>
                    <td colSpan={9} className={styles.empty}>
                      No doctors in this section
                    </td>
                  </tr>
                ) : (
                  doctors.map((doctor, i) => (
                    <tr key={doctor.id}>
                      <td>{i + 1}</td>
                      <td>
                        <img
                          src={doctor.image_url ?? '/Dashboard/img/doctor_default.png'}
                          alt={doctor.name}
                          className={styles.avatar}
                        />
                      </td>
                      <td>{doctor.name}</td>
                      <td>{doctor.email}</td>
                      <td>{doctor.phone}</td>
                      <td>{doctor.appointments?.length || '—'}</td>
                      <td>
                        <span className={doctor.is_active ? styles.badgeActive : styles.badgeInactive}>
                          <span className={styles.dot} />
                          {doctor.is_active ? 'Active' : 'Inactive'}
                        </span>
                      </td>
                      <td>{timeAgo(doctor.created_at, 'en')}</td>
                      <td>
                        <div className={styles.dropdown}>
                          <button className={styles.dropdownBtn}
                            onClick={() => setOpenDropdown(
                              openDropdown === doctor.id ? null : doctor.id
                            )}>
                            Actions ▾
                          </button>
                          {openDropdown === doctor.id && (
                            <div className={styles.dropdownMenu}>
                              <button onClick={() => { setPasswordDoctor(doctor); setOpenDropdown(null); }}>
                                🔑 Change Password
                              </button>
                              <button onClick={() => { setStatusDoctor(doctor); setOpenDropdown(null); }}>
                                🔄 Change Status
                              </button>
                              <button onClick={() => { setDeleteDoctor(doctor); setOpenDropdown(null); }}
                                className={styles.dropdownDanger}>
                                🗑️ Delete
                              </button>
                            </div>
                          )}
                        </div>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {passwordDoctor && (
        <UpdatePasswordModal doctor={passwordDoctor}
          onClose={() => setPasswordDoctor(null)} />
      )}
      {statusDoctor && (
        <UpdateStatusModal doctor={statusDoctor}
          onClose={() => setStatusDoctor(null)} />
      )}
      {deleteDoctor && (
        <DeleteModal name={deleteDoctor.name}
          deleteUrl={deleteDoctor.delete_url}
          onClose={() => setDeleteDoctor(null)} />
      )}

    </DashboardLayout>
  );
}