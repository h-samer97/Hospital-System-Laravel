import React, { useState, useCallback, useMemo } from 'react';
import { Head, router } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Toast from '@/Components/UI/Toast';
import SkeletonRow from '@/Components/UI/SkeletonRow';
// import PaymentFormModal from './PaymentFormModal';
import DeleteModal from '@/Components/UI/DeleteModal';
import { useDebounce } from '@/hooks/useDebounce';
import type { Payment } from './types';
import { timeAgo } from '@/utils/date';
import styles from './Index.module.css';
import { PaginatedData } from '../Receipts/types';
import { Patient } from '../Patients/types';
import PaymentFormModal from './PaymentFormModal';

interface ModalState {
  mode: 'add' | 'edit';
  payment: Payment | null;
}

interface Props {
  payments: PaginatedData<Payment>;
  patients: Pick<Patient, 'id' | 'name'>[];
  store_url: string;
}

export default function PaymentsIndex({ payments, patients, store_url }: Props) {
  const [modal, setModal] = useState<ModalState | null>(null);
  const [deletePayment, setDeletePayment] = useState<Payment | null>(null);
  const [search, setSearch] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const debouncedSearch = useDebounce(search, 300);

  const filtered = useMemo(() =>
    payments.data.filter(p =>
      p.patient?.toLowerCase().includes(debouncedSearch.toLowerCase()) ||
      p.description.toLowerCase().includes(debouncedSearch.toLowerCase())
    ),
    [payments.data, debouncedSearch]
  );

  const handlePageChange = useCallback((page: number) => {
    setIsLoading(true);
    router.get(
      route('payments.index'),
      { page },
      {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => setIsLoading(false),
      }
    );
  }, []);

  const openAdd = useCallback(() => setModal({ mode: 'add', payment: null }), []);
  const closeModal = useCallback(() => setModal(null), []);

  return (
    <DashboardLayout title={'Payment Accounts'}>
      <Head title="Payment Accounts — سندات الصرف" />
      <Toast />

      <div className={styles.page}>

        {/* ===== Header ===== */}
        <div className={styles.header}>
          <div>
            <h1>Payment Accounts</h1>
            <p className={styles.breadcrumb}>Dashboard / Finance / Payments</p>
          </div>
          <button className={styles.addBtn} onClick={openAdd}>
            + New Payment
          </button>
        </div>

        {/* ===== Stats ===== */}
        <div className={styles.statsBar}>
          <div className={styles.stat}>
            <span>Total Records</span>
            <strong>{payments.total}</strong>
          </div>
          <div className={styles.stat}>
            <span>Page</span>
            <strong>{payments.current_page} / {payments.last_page}</strong>
          </div>
          <div className={styles.stat}>
            <span>Showing</span>
            <strong>{filtered.length} results</strong>
          </div>
        </div>

        {/* ===== Table Card ===== */}
        <div className={styles.card}>

          {/* Search Toolbar */}
          <div className={styles.toolbar}>
            <div className={styles.searchWrapper}>
              <span className={styles.searchIcon}>🔍</span>
              <input
                type="text"
                placeholder="Search by patient or description..."
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className={styles.searchInput}
              />
              {search !== debouncedSearch && (
                <span className={styles.searchSpinner}>⏳</span>
              )}
              {search && (
                <button
                  className={styles.clearSearch}
                  onClick={() => setSearch('')}
                  aria-label="Clear search"
                >✕</button>
              )}
            </div>
          </div>

          {/* Table */}
          <div className={styles.tableWrapper}>
            <table className={styles.table}>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Patient</th>
                  <th>Amount</th>
                  <th>Description</th>
                  <th>Added</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {isLoading ? (
                  <SkeletonRow columns={7} rows={8} />
                ) : filtered.length === 0 ? (
                  <tr>
                    <td colSpan={7} className={styles.empty}>
                      {search ? `No results for "${search}"` : 'No payments found'}
                    </td>
                  </tr>
                ) : (
                  filtered.map((payment, i) => (
                    <tr key={payment.id}>
                      <td className={styles.rowNumber}>
                        {(payments.current_page - 1) * payments.per_page + i + 1}
                      </td>
                      <td>{payment.date}</td>
                      <td>
                        <div className={styles.patientCell}>
                          <span className={styles.avatar}>
                            {payment.patient?.charAt(0).toUpperCase() ?? '?'}
                          </span>
                          {payment.patient ?? '—'}
                        </div>
                      </td>
                      <td>
                        <span className={styles.amount}>
                          {Number(payment.amount).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                          })} EGP
                        </span>
                      </td>
                      <td className={styles.desc}>
                        {payment.description.length > 50
                          ? `${payment.description.slice(0, 50)}…`
                          : payment.description}
                      </td>
                      <td title={new Date(payment.created_at).toLocaleString()}>
                        {timeAgo(payment.created_at, 'en')}
                      </td>
                      <td>
                        <div className={styles.actions}>
                          <button
                            className={styles.editBtn}
                            onClick={() => setModal({ mode: 'edit', payment })}
                            title="Edit"
                          >✏️</button>
                          <button
                            className={styles.deleteBtn}
                            onClick={() => setDeletePayment(payment)}
                            title="Delete"
                          >🗑️</button>
                        </div>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>

          {/* ===== Pagination ===== */}
          {payments.last_page > 1 && !isLoading && (
            <div className={styles.pagination}>
              <span className={styles.paginationInfo}>
                Showing&nbsp;
                <strong>{(payments.current_page - 1) * payments.per_page + 1}</strong>
                &nbsp;–&nbsp;
                <strong>
                  {Math.min(payments.current_page * payments.per_page, payments.total)}
                </strong>
                &nbsp;of&nbsp;<strong>{payments.total}</strong>
              </span>

              <div className={styles.paginationBtns}>
                <button
                  className={styles.pageBtn}
                  onClick={() => handlePageChange(1)}
                  disabled={payments.current_page === 1}
                  title="First page"
                >«</button>

                <button
                  className={styles.pageBtn}
                  onClick={() => handlePageChange(payments.current_page - 1)}
                  disabled={payments.current_page === 1}
                >‹ Prev</button>

                {Array.from({ length: payments.last_page }, (_, i) => i + 1)
                  .filter(p =>
                    p === 1 ||
                    p === payments.last_page ||
                    Math.abs(p - payments.current_page) <= 1
                  )
                  .map((p, idx, arr) => (
                    <React.Fragment key={p}>
                      {idx > 0 && p - arr[idx - 1] > 1 && (
                        <span className={styles.dots}>…</span>
                      )}
                      <button
                        className={`${styles.pageBtn} ${p === payments.current_page ? styles.pageBtnActive : ''
                          }`}
                        onClick={() => handlePageChange(p)}
                      >
                        {p}
                      </button>
                    </React.Fragment>
                  ))}

                <button
                  className={styles.pageBtn}
                  onClick={() => handlePageChange(payments.current_page + 1)}
                  disabled={payments.current_page === payments.last_page}
                >Next ›</button>

                <button
                  className={styles.pageBtn}
                  onClick={() => handlePageChange(payments.last_page)}
                  disabled={payments.current_page === payments.last_page}
                  title="Last page"
                >»</button>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* ===== Modals ===== */}
      {modal && (
        <PaymentFormModal
          mode={modal.mode}
          payment={modal.payment ?? undefined}
          patients={patients}
          url_store={
            modal.mode === 'add'
              ? store_url
              : modal.payment!.urls.update
          }
          onClose={closeModal}
        />
      )}

      {deletePayment && (
        <DeleteModal
          name={`Payment #${deletePayment.id} — ${deletePayment.patient}`}
          deleteUrl={deletePayment.urls.destroy}
          onClose={() => setDeletePayment(null)}
        />
      )}

    </DashboardLayout>
  );
}