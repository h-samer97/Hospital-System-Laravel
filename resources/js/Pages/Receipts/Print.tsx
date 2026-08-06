import React from 'react';
import { Head } from '@inertiajs/react';
import PrintLayout from '@/Layouts/PrintLayout';
import InfoRow from '@/Components/InfoRow';
import type { PrintableReceipt } from '@/types/models';
import styles from './Print.module.css';

interface Props {
  receipt:     PrintableReceipt;
  print_count: number;
  urls?: {
    print:    string;
    download: string;
  };
}

export default function ReceiptPrint({ receipt, print_count, urls }: Props) {
  return (
    <>
      <Head title={`Receipt #${receipt.id}`} />

      <PrintLayout
        title="Receipt Voucher"
        documentType="سند قبض"
        documentId={receipt.id}
        printCount={print_count}
        downloadUrl={urls?.download}
      >

        {/* بيانات المريض */}
        <section className={styles.section}>
          <h3 className={styles.sectionTitle}>Patient Information</h3>
          <div className={styles.infoGrid}>
            <InfoRow label="Patient Name" value={receipt.patient} />
            <InfoRow label="Phone"        value={receipt.phone} />
            <InfoRow label="Address"      value={receipt.address} />
          </div>
        </section>

        {/* بيانات السند */}
        <section className={styles.section}>
          <h3 className={styles.sectionTitle}>Receipt Details</h3>
          <div className={styles.infoGrid}>
            <InfoRow label="Receipt #"  value={`#${receipt.id}`} mono />
            <InfoRow label="Date"       value={receipt.date} />
            <InfoRow label="Issued At"  value={receipt.created_at} />
          </div>
        </section>

        {/* الوصف */}
        <section className={styles.section}>
          <h3 className={styles.sectionTitle}>Description</h3>
          <p className={styles.description}>{receipt.description}</p>
        </section>

        {/* المبلغ */}
        <section className={`${styles.section} ${styles.amountSection}`}>
          <div className={styles.amountBox}>
            <span className={styles.amountLabel}>Amount Received</span>
            <span className={styles.amountValue}>
              {Number(receipt.debit).toLocaleString('en-US', {
                minimumFractionDigits: 2,
              })} EGP
            </span>
          </div>
        </section>

        <div className={styles.voucherType}>
          <span className={styles.voucherTypeBadge}>
            🧾 Receipt Voucher — سند قبض
          </span>
        </div>

      </PrintLayout>
    </>
  );
}