import { useState, type FC } from 'react';
import { Head } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Toast from '@/Components/UI/Toast';
import FormModal from './AmbulanceFormModal';
import DeleteModal from '@/Components/UI/DeleteModal';
import type { Ambulance, IndexProps } from './types';
import styles from './Index.module.css';

// ModalState — نمط موحد استخدمناه في كل الموديولات
interface ModalState {
  mode: 'add' | 'edit';
  ambulance: Ambulance | null;
}

const AmbulancesIndex: FC<IndexProps> = ({ ambulances, store_url }) => {

  // ✅ state واحد يتحكم في الـ modal (add/edit)
  const [modal, setModal] = useState<ModalState | null>(null);
  // ✅ ambulance محدد للحذف
  const [deleteAmbulance, setDeleteAmbulance] = useState<Ambulance | null>(null);
  const [search, setSearch] = useState('');

  const filtered = ambulances.filter(a =>
    a.driver_name.toLowerCase().includes(search.toLowerCase()) ||
    a.car_number.toLowerCase().includes(search.toLowerCase()) ||
    a.car_model.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <>
      <Head title="Ambulances" />
      <Toast />

      <DashboardLayout title={''}>
        <div className={styles.page}>

          <div className={styles.header}>
            <div>
              <h1>Ambulances</h1>
              <p className={styles.breadcrumb}>Dashboard / Ambulances</p>
            </div>
            {/* ✅ فتح modal الإضافة */}
            <button
              className={styles.addBtn}
              onClick={() => setModal({ mode: 'add', ambulance: null })}
            >
              + Add Ambulance
            </button>
          </div>

          <div className={styles.card}>
            <div className={styles.toolbar}>
              <input
                type="text"
                placeholder="Search by number, model or driver..."
                className={styles.searchInput}
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
              <span className={styles.count}>{filtered.length} ambulances</span>
            </div>

            <div className={styles.tableWrapper}>
              <table className={styles.table}>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Car Number</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Type</th>
                    <th>Driver</th>
                    <th>License No.</th>
                    <th>Phone</th>
                    <th>Availability</th>
                    <th>Notes</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {filtered.length === 0 ? (
                    <tr>
                      <td colSpan={11} className={styles.empty}>
                        No ambulances found
                      </td>
                    </tr>
                  ) : (
                    filtered.map((ambulance, i) => (
                      <tr key={ambulance.id}>
                        <td>{i + 1}</td>
                        <td>
                          <span className={styles.carNumber}>
                            {ambulance.car_number}
                          </span>
                        </td>
                        <td>{ambulance.car_model}</td>
                        {/* ✅ car_year_made رقم مباشر وليس Date */}
                        <td>{ambulance.car_year_made}</td>
                        <td>
                          <span className={
                            ambulance.car_type === 'owned'
                              ? styles.badgeOwned
                              : styles.badgeRental
                          }>
                            
                          </span>
                        </td>
                        <td>{ambulance.driver_name}</td>
                        <td>{ambulance.driver_license_number}</td>
                        <td>{ambulance.driver_phone}</td>
                        <td>
                          <span className={
                            ambulance.is_available
                              ? styles.badgeActive
                              : styles.badgeInactive
                          }>
                            <span className={styles.dot} />
                            {ambulance.is_available ? 'Available' : 'Unavailable'}
                          </span>
                        </td>
                        <td className={styles.notes}>
                          {ambulance.notes?.slice(0, 30) ?? '—'}
                        </td>
                        <td className={styles.actions}>
                          {/* ✅ يمرر الـ ambulance كاملاً */}
                          <button
                            className={styles.editBtn}
                            onClick={() => setModal({ mode: 'edit', ambulance })}
                          >
                            ✏️
                          </button>
                          {/* ✅ يحفظ الـ ambulance المراد حذفه */}
                          <button
                            className={styles.deleteBtn}
                            onClick={() => setDeleteAmbulance(ambulance)}
                          >
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
      </DashboardLayout>

      {/* ✅ modal واحد لـ add و edit */}
      {modal && (
        <FormModal
          mode={modal.mode}
          ambulance={modal.ambulance}
          url_store={
            modal.mode === 'add'
              ? store_url                          // POST /ambulances
              : modal.ambulance!.urls.update       // PUT /ambulances/{id}
          }
          onClose={() => setModal(null)}
        />
      )}

      {/* ✅ deleteUrl من الـ ambulance نفسه وليس من المصفوفة */}
      {deleteAmbulance && (
        <DeleteModal
          name={deleteAmbulance.car_number}
          deleteUrl={deleteAmbulance.urls.destroy}  // ✅ destroy وليس delete
          onClose={() => setDeleteAmbulance(null)}
        />
      )}

    </>
  );
};

export default AmbulancesIndex;