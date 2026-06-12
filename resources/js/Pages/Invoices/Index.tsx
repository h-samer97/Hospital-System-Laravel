import { SingleInvoice } from "./types";
import styles from "./Index.module.css";
import { Patient } from "../Patients/types";
import { DoctorSection } from "../Doctors/types";
import { Services } from "../Services/types";
import { useState } from "react";
import InvoiceForm from "./InvoiceForm";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head } from "@inertiajs/react";
import Toast from "@/Components/UI/Toast";
import DeleteModal from "@/Components/UI/DeleteModal";

// TODO

interface Props {
  invoices: SingleInvoice[];
  patients: Pick<Patient, 'name' | 'id'>[];
  doctors: DoctorSection[];
  services: Pick<Services, 'name' | 'id' | 'price'>[];
  onClose: () => void;
  store_url: string;

}
interface ModelState {
  mode: 'edit' | 'add',
  invoices: SingleInvoice | null;
}

const InvoiceIndex = ({ invoices = [], onClose, store_url, patients, doctors, services }: Props) => {

  const [formModel, setFormModel] = useState<ModelState | false>(false);
  const [showForm, setShowForm] = useState<boolean>(false);
  const [search, setSearch] = useState('');
  const [modal, setModal] = useState<ModelState | null>(null);
  const [deleteInvoice, setDeleteInvoice] = useState<SingleInvoice | null>(null);

  const q = search.toLowerCase();
  const filtered = (invoices ?? []).filter(inv => {
    const patient = inv.patient ? inv.patient.toLowerCase() : '';
    const doctor = inv.doctor ? inv.doctor.toLowerCase() : '';
    const serviceName = inv.service ? inv.service.toLowerCase() : '';
    return patient.includes(q) || doctor.includes(q) || serviceName.includes(q);
  });

  return (
    <>
      <DashboardLayout title="Single Invoices">
        <Head />
        <Toast />
        <div className={styles.page}>
          <div className={styles.header}>
            <div>
              <h1>Single Service Invoices</h1>
              <p className={styles.breadcrumb}>Dashboard / Invoices / Single</p>
            </div>
            <button className={styles.addBtn} onClick={() => setShowForm(!showForm)}>
              {showForm ? '✕ Cancel' : '+ New Invoice'}
            </button>
            {showForm && (
              <InvoiceForm mode='add' patients={patients} doctors={doctors as any} services={services} store_url={store_url} onSuccess={() => setShowForm(false)} />
            )}
          </div>

          {modal?.mode === 'edit' && modal.invoices && (
            <InvoiceForm mode='edit' patients={patients} doctors={doctors as any} services={services} store_url={store_url} invoice={modal.invoices} onSuccess={() => setModal(null)} />
          )}

          <div className={styles.card}>
            <div className={styles.toolbar}>
              <input
                type="text"
                placeholder="Search by patient, doctor or service..."
                className={styles.searchInput}
                onChange={(e) => setSearch(e.target.value)}
                value={search}
              />
              <span className={styles.count}>{filtered.length} invoices</span>
            </div>

            <div className={styles.tableWrapper}>
              <table className={styles.table}>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Section</th>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Tax %</th>
                    <th>Total</th>
                    <th>Type</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>

                  {filtered.length === 0 ? (
                    <tr>
                      <td colSpan={12} className={styles.empty}>No invoices found</td>
                    </tr>
                  ) : (
                    filtered.map((invoice, i) => (
                      <tr key={invoice.id}>
                        <td>{i + 1}</td>
                        <td>{invoice.invoice_date}</td>
                        <td>{invoice.patient ?? 'N/A'}</td>
                        <td>{invoice.doctor ?? 'N/A'}</td>
                        <td>{invoice.section ?? 'N/A'}</td>
                        <td>{invoice.service ?? 'N/A'}</td>
                        <td>{Number(invoice.price).toLocaleString() ?? 'N/A'}</td>
                        <td>{Number(invoice.discount_value).toLocaleString() ?? 'N/A'}</td>
                        <td>{invoice.tax_rate}%</td>
                        <td style={{ color: 'red' }}>
                          {invoice.total_with_tax}
                        </td>
                        <td>
                          <span className={invoice.type === 'cash'
                            ? styles.badgeCash : styles.badgeDeferred}>
                            {invoice.type_label}
                          </span>
                        </td>
                        <td className={styles.actions}>
                          <button className={styles.editBtn}
                            onClick={() => setModal({ mode: 'edit', invoices: invoice })}>
                            ✏️
                          </button>
                          <button className={styles.deleteBtn}
                            onClick={() => setDeleteInvoice(invoice)}>
                            🗑️
                          </button>
                        </td>
                      </tr>
                    ))
                  )}

                  {deleteInvoice && (
                    <DeleteModal
                      name={`Invoice #${deleteInvoice.id}`}
                      deleteUrl={deleteInvoice.urls.destroy}
                      onClose={() => setDeleteInvoice(null)}
                    />
                  )}

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </DashboardLayout>
    </>
  );

}

export default InvoiceIndex;