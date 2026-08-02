import React from 'react';
import { Head } from '@inertiajs/react';
import PrintLayout from '@/Layouts/PrintLayout';
import InfoRow from '@/Components/Print/InfoRow';
import type { PrintablePayment } from '@/types/models';
import styles from './Print.module.css';

interface Props {
  payment:     PrintablePayment;
  print_count: number;
  urls?: {
    print:    string;
    download: string;
  };
}

export default function PaymentPrint({ payment, print_count, urls }: Props) {
  return (
    <>
      <Head title={`Payment #${payment.id}`} />

      <PrintLayout
        title="Payment Voucher"
        documentType="سند دفع"
        documentId={payment.id}
        printCount={print_count}
        downloadUrl={urls?.download}
      >

        {/* بيانات المريض */}
        <section className={styles.section}>
          <h3 className={styles.sectionTitle}>Patient Information</h3>
          <div className={styles.infoGrid}>
            <InfoRow label="Patient Name" value={payment.patient} />
            <InfoRow label="Phone"        value={payment.phone} />
            <InfoRow label="Address"      value={payment.address} />
          </div>
        </section>

        {/* بيانات السند */}
        <section className={styles.section}>
          <h3 className={styles.sectionTitle}>Payment Details</h3>
          <div className={styles.infoGrid}>
            <InfoRow label="Payment #"  value={`#${payment.id}`} mono />
            <InfoRow label="Date"       value={payment.date} />
            <InfoRow label="Issued At"  value={payment.created_at} />
          </div>
        </section>

        {/* الوصف */}
        <section className={styles.section}>
          <h3 className={styles.sectionTitle}>Description</h3>
          <p className={styles.description}>{payment.description}</p>
        </section>

        {/* المبلغ */}
        <section className={`${styles.section} ${styles.amountSection}`}>
          <div className={styles.amountBox}>
            <span className={styles.amountLabel}>Amount Paid</span>
            <span className={styles.amountValue}>
              {Number(payment.amount).toLocaleString('en-US', {
                minimumFractionDigits: 2,
              })} EGP
            </span>
          </div>
        </section>

        <div className={styles.voucherType}>
          <span className={styles.voucherTypeBadge}>
            💳 Payment Voucher — سند دفع
          </span>
        </div>

      </PrintLayout>
    </>
  );
}