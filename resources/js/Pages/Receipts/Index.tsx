import React, { useState, useCallback } from 'react';
import { Head, router } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Toast from '@/Components/UI/Toast';
import ReceiptFormModal from './ReceiptFormModal';
import DeleteModal from '@/Components/UI/DeleteModal';
import type { Receipt, PaginatedData } from './types';
import type { Patient } from '@/Pages/Patients/types';
import { timeAgo } from '@/utils/date';
import styles from './Index.module.css';

interface ModalState {
  mode: 'add' | 'edit';
  receipt: Receipt | null;
}

interface Props {
  receipts: PaginatedData<Receipt>;
  patients: Pick<Patient, 'id' | 'name'>[];
  store_url: string;
}

export default function ReceiptsIndex({ receipts, patients, store_url }: Props) {
  const [modal, setModal] = useState<ModalState | null>(null);
  const [deleteReceipt, setDeleteReceipt] = useState<Receipt | null>(null);

  // ✅ useCallback — لا تُعاد إنشاء الدالة عند كل render
  const handlePageChange = useCallback((page: number) => {
    router.get(route('Receipt.index'), { page }, {
      preserveState: true,   // يحافظ على الـ scroll position
      preserveScroll: true,
    });
  }, []);

  return (
    <DashboardLayout title='Receipt Manage'>
      <Head title="Receipt Accounts" />
      <Toast />

      <div className={styles.page}>
        <div className={styles.header}>
          <div>
            <h1>Receipt Accounts</h1>
            <p className={styles.breadcrumb}>Dashboard / Finance / Receipts</p>
          </div>
          <button className={styles.addBtn}
            onClick={() => setModal({ mode: 'add', receipt: null })}>
            + New Receipt
          </button>
        </div>

        {/* Stats Bar */}
        <div className={styles.statsBar}>
          <div className={styles.stat}>
            <span>Total Records</span>
            <strong>{receipts.total}</strong>
          </div>
          <div className={styles.stat}>
            <span>Current Page</span>
            <strong>{receipts.current_page} / {receipts.last_page}</strong>
          </div>
        </div>

        <div className={styles.card}>
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
                {receipts.data.length === 0 ? (
                  <tr>
                    <td colSpan={7} className={styles.empty}>
                      No receipts found
                    </td>
                  </tr>
                ) : (
                  receipts.data.map((receipt, i) => (
                    <tr key={receipt.id}>
                      <td>
                        {/* الرقم الحقيقي بحسب الصفحة الحالية */}
                        {(receipts.current_page - 1) * receipts.per_page + i + 1}
                      </td>
                      <td>{receipt.date}</td>
                      <td>{receipt.patient ?? '—'}</td>
                      <td className={styles.amount}>
                        {Number(receipt.debit).toLocaleString()} EGP
                      </td>
                      <td className={styles.desc}>
                        {receipt.description.slice(0, 50)}
                        {receipt.description.length > 50 ? '...' : ''}
                      </td>
                      <td title={new Date(receipt.created_at).toLocaleDateString()}>
                        {timeAgo(receipt.created_at, 'en')}
                      </td>
                      <td className={styles.actions}>
                        <button className={styles.editBtn}
                          onClick={() => setModal({ mode: 'edit', receipt })}>
                          ✏️
                        </button>
                        <button className={styles.deleteBtn}
                          onClick={() => setDeleteReceipt(receipt)}>
                          🗑️
                        </button>
                      </td>
                      // في الجدول — عمود Actions
<td>
  <div className={styles.actions}>

    {/* Edit */}
    <button className={styles.editBtn}
      onClick={() => setModal({ mode: 'edit', payment })}
      title="Edit">✏️</button>

    {/* ✅ Print — يفتح صفحة الطباعة في نافذة جديدة */}
    
      href={payment.urls.print}
      target="_blank"
      rel="noopener noreferrer"
      className={styles.printBtn}
      title="Print"
    >🖨️</a>

    {/* ✅ Download PDF */}
    
      href={payment.urls.download}
      target="_blank"
      rel="noopener noreferrer"
      className={styles.downloadBtn}
      title="Download PDF"
    >⬇️</a>

    {/* Delete */}
    <button className={styles.deleteBtn}
      onClick={() => setDeletePayment(payment)}
      title="Delete">🗑️</button>

  </div>
</td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>

          {/* ===== Pagination ===== */}
          {receipts.last_page > 1 && (
            <div className={styles.pagination}>
              <span className={styles.paginationInfo}>
                Showing {(receipts.current_page - 1) * receipts.per_page + 1}
                –{Math.min(receipts.current_page * receipts.per_page, receipts.total)}
                &nbsp;of {receipts.total}
              </span>

              <div className={styles.paginationBtns}>
                <button className={styles.pageBtn}
                  disabled={receipts.current_page === 1}
                  onClick={() => handlePageChange(receipts.current_page - 1)}>
                  ‹ Prev
                </button>

                {Array.from({ length: receipts.last_page }, (_, i) => i + 1)
                  .filter(p =>
                    p === 1 ||
                    p === receipts.last_page ||
                    Math.abs(p - receipts.current_page) <= 1
                  )
                  .map((p, idx, arr) => (
                    <React.Fragment key={p}>
                      {idx > 0 && p - (arr[idx - 1]) > 1 && (
                        <span className={styles.dots}>...</span>
                      )}
                      <button
                        className={`${styles.pageBtn} ${p === receipts.current_page ? styles.pageBtnActive : ''
                          }`}
                        onClick={() => handlePageChange(p)}>
                        {p}
                      </button>
                    </React.Fragment>
                  ))
                }

                <button className={styles.pageBtn}
                  disabled={receipts.current_page === receipts.last_page}
                  onClick={() => handlePageChange(receipts.current_page + 1)}>
                  Next ›
                </button>
              </div>
            </div>
          )}
        </div>
      </div>

      {modal && (
        <ReceiptFormModal
          mode={modal.mode}
          receipt={modal.receipt ?? undefined}
          patients={patients}
          storeUrl={
            modal.mode === 'add'
              ? store_url
              : modal.receipt!.urls.update
          }
          onClose={() => setModal(null)}
        />
      )}

      {deleteReceipt && (
        <DeleteModal
          name={`Receipt #${deleteReceipt.id} — ${deleteReceipt.patient}`}
          deleteUrl={deleteReceipt.urls.destroy}
          onClose={() => setDeleteReceipt(null)}
        />
      )}

    </DashboardLayout>
  );
}