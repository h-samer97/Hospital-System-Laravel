import React from 'react';
import { Head } from '@inertiajs/react';
import PrintLayout from '@/Layouts/PrintLayout';
import InfoRow from '@/Components/InfoRow';
import type { PrintableInvoice } from '@/types/models';
import styles from './Print.module.css';

interface Props {
  invoice: PrintableInvoice;
  print_count: number;
  urls?: {
    print: string;
    download: string;
  };
}

export default function InvoicePrint({ invoice, print_count, urls }: Props) {
  const subtotal = Number(invoice.price) - Number(invoice.discount_value);

  return (
    <>
      <Head title={`Invoice #${invoice.id}`} />

      <PrintLayout
        title="Service Invoice"
        documentType={invoice.type === 'cash' ? 'Cash Invoice' : 'Deferred Invoice'}
        documentId={invoice.id}
        printCount={print_count}
        downloadUrl={urls?.download}
      >

        {/* نوع الفاتورة Badge */}
        <div className={styles.invoiceTypeBanner}>
          <span className={invoice.type === 'cash'
            ? styles.badgeCash : styles.badgeDeferred}>
            {invoice.type === 'cash' ? '💵 Cash Invoice' : '📋 Deferred Invoice'}
          </span>
          <span className={styles.invoiceDate}>
            Date: {invoice.invoice_date}
          </span>
        </div>

        {/* بيانات الطرفين */}
        <div className={styles.partiesGrid}>

          {/* المريض */}
          <section className={styles.partyBox}>
            <h3 className={styles.partyTitle}>👤 Patient</h3>
            <InfoRow label="Name" value={invoice.patient} />
            <InfoRow label="Phone" value={invoice.phone} />
          </section>

          {/* الطبيب والقسم */}
          <section className={styles.partyBox}>
            <h3 className={styles.partyTitle}>👨‍⚕️ Doctor</h3>
            <InfoRow label="Name" value={invoice.doctor} />
            <InfoRow label="Section" value={invoice.section} />
          </section>

        </div>

        {/* جدول الخدمة */}
        <section className={styles.section}>
          <h3 className={styles.sectionTitle}>Service Details</h3>

          <table className={styles.serviceTable}>
            <thead>
              <tr>
                <th>#</th>
                <th>Service</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>{invoice.service}</td>
                <td className={styles.numCell}>
                  {Number(invoice.price).toLocaleString()} EGP
                </td>
                <td className={styles.numCell}>
                  {Number(invoice.discount_value).toLocaleString()} EGP
                </td>
                <td className={styles.numCell}>
                  {subtotal.toLocaleString()} EGP
                </td>
              </tr>
            </tbody>
          </table>
        </section>

        {/* ===== جدول الإجماليات ===== */}
        <section className={styles.totalsSection}>
          <div className={styles.totalsBox}>

            <div className={styles.totalRow}>
              <span>Subtotal</span>
              <span>{subtotal.toLocaleString('en-US', { minimumFractionDigits: 2 })} EGP</span>
            </div>

            <div className={styles.totalRow}>
              <span>Tax ({invoice.tax_rate}%)</span>
              <span>{Number(invoice.tax_value).toLocaleString('en-US', { minimumFractionDigits: 2 })} EGP</span>
            </div>

            <div className={`${styles.totalRow} ${styles.grandTotal}`}>
              <span>Total</span>
              <span>
                {Number(invoice.total_with_tax).toLocaleString('en-US', {
                  minimumFractionDigits: 2,
                })} EGP
              </span>
            </div>

          </div>
        </section>

        {/* معرف الفاتورة للمسح */}
        <div className={styles.invoiceId}>
          <span>Invoice ID: </span>
          <code>INV-{String(invoice.id).padStart(6, '0')}</code>
        </div>

      </PrintLayout>
    </>
  );
}