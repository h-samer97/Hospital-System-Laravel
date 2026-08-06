import { useForm } from "@inertiajs/react";
import { Patient } from "../Patients/types";
import { Services } from "../Services/types";
import { InvoiceCalculation, InvoiceDoctor, InvoiceFormData, SingleInvoice } from "./types";
import { FormEvent, useEffect, useMemo } from "react";
import styles from './InvoiceForm.module.css';



interface Props {
  mode: 'edit' | 'add';
  invoice?: SingleInvoice;
  patients: Pick<Patient, 'name' | 'id'>[];
  doctors: InvoiceDoctor[];
  services: Pick<Services, 'name' | 'id' | 'price'>[];
  store_url: string;
  onSuccess: () => void;
}

const InvoiceForm = ({ mode, invoice, patients, doctors, services, store_url, onSuccess }: Props) => {

  const isEdit = mode === 'edit';
  const targetUrl = isEdit ? invoice!.urls.update : store_url;

  const { data, setData, errors, processing, post, put, reset } = useForm<InvoiceFormData>({

    patient_id: invoice?.patient_id != null ? Number(invoice.patient_id) : '',
    doctor_id: invoice?.doctor_id != null ? Number(invoice.doctor_id) : '',
    section_id: invoice?.section_id != null ? Number(invoice.section_id) : '',
    service_id: invoice?.service_id != null ? Number(invoice.service_id) : '',
    price: invoice?.price != null ? Number(invoice.price) : '',
    discount_value: invoice?.discount_value != null ? Number(invoice.discount_value) : 0,
    tax_rate: invoice?.tax_rate != null ? Number(invoice.tax_rate) : 0,
    type: invoice?.type ?? '',

  });

  useEffect(() => {

    if (!data.doctor_id) return;

    const selectedDoctor = doctors.find(d => d.id === Number(data.doctor_id));

    if (selectedDoctor) {
      setData('section_id', selectedDoctor.section_id);
    }

  }, [data.doctor_id]);

  // When editing, ensure the form state is populated from the incoming `invoice` prop.
  useEffect(() => {
    if (isEdit && invoice) {
      setData('patient_id', invoice.patient_id ?? '');
      setData('doctor_id', invoice.doctor_id ?? '');
      setData('section_id', invoice.section_id ?? '');
      setData('service_id', invoice.service_id ?? '');
      setData('price', invoice.price != null ? Number(invoice.price) : '');
      setData('discount_value', invoice.discount_value != null ? Number(invoice.discount_value) : 0);
      setData('tax_rate', invoice.tax_rate != null ? Number(invoice.tax_rate) : 0);
      setData('type', invoice.type ?? '');
    } else if (!isEdit) {
      // reset to defaults when switching to add mode
      reset();
    }
  }, [isEdit, invoice]);

  useEffect(() => {

    if (!data.service_id) return;

    const selectedService = services.find(serv => serv.id === Number(data.service_id));

    if (selectedService) {
      setData('price', Number(selectedService.price));
    }

  }, [data.service_id]);


  const calc = useMemo((): InvoiceCalculation => {

    const price = Number(data.price) || 0;
    const discount = Number(data.discount_value) || 0;
    const taxRate = Number(data.tax_rate) || 0;

    const subtotal = price - discount;
    const taxValue = (subtotal * taxRate) / 100;
    const total = subtotal + taxValue;

    return {
      total: Math.max(0, total),
      subtotal: Math.max(0, subtotal),
      tax_value: Math.max(0, taxValue)
    }


  }, [data.price, data.discount_value, data.tax_rate]);

  const handleSubmit = (e: FormEvent) => {

    e.preventDefault();

    const opts = {
      onSuccess: () => {
        reset();
        onSuccess();
      }
    }


    if (isEdit) {
      put(targetUrl, opts);
    } else {
      post(targetUrl, opts);
    }

  }

  return (
    <>
      <div className={styles.formCard} >
        <h2 className={styles.formTitle}>
          {isEdit ? 'Edit Invoice' : 'New Single Service Invoice'}
        </h2>

        <form onSubmit={handleSubmit}>
          <div className={styles.body}>

            {/* Row 1: Patient + Doctor + Section + Type */}
            <div className={styles.row4}>
              <div className={styles.formGroup}>
                <label>Patient</label>
                <select className={styles.input} value={data.patient_id ?? ''} onChange={(e) => setData('patient_id', e.target.value === '' ? '' : Number(e.target.value))}>
                  <option value="">-- Select Patient --</option>
                  {patients.map(p => (
                    <option key={p.id} value={p.id}>{p.name}</option>
                  ))}
                </select>
                {/* Example Error State */}
                {/* <select className={styles.inputError}></select>
          <span className={styles.errorMsg}>Patient field is required</span> */}
              </div>

              <div className={styles.formGroup}>
                <label>Doctor</label>
                <select className={styles.input} value={data.doctor_id ?? ''} onChange={(e) => setData('doctor_id', e.target.value === '' ? '' : Number(e.target.value))}>
                  <option value="">-- Select Doctor --</option>
                  {doctors.map(d => (
                    <option key={d.id} value={d.id}>{d.name}</option>
                  ))}
                </select>
              </div>

              {/* Section (Auto Filled - Read Only) */}
              <div className={styles.formGroup}>
                <label>Section <small>(auto)</small></label>
                <input
                  type="text"
                  readOnly
                  className={`${styles.input} ${styles.readOnly}`}
                  placeholder="— auto filled —"
                  value={(() => {
                    const sel = doctors.find(d => d.id === Number(data.doctor_id));
                    return sel?.section_name ?? '';
                  })()}
                />
              </div>

              <div className={styles.formGroup}>
                <label>Invoice Type</label>
                <select className={styles.input} value={data.type ?? ''} onChange={(e) => setData('type', e.target.value)}>
                  <option value="">-- Choose --</option>
                  <option value="cash">💵 Cash</option>
                  <option value="deferred">📋 Deferred</option>
                </select>
              </div>
            </div>

            {/* Service Table Wrapper */}
            <div className={styles.tableWrapper}>
              <table className={styles.table}>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Tax %</th>
                    <th>Tax Value</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>

                    {/* Service Selection */}
                    <td>
                      <select className={styles.input} value={data.service_id ?? ''} onChange={(e) => setData('service_id', e.target.value === '' ? '' : Number(e.target.value))}>
                        <option value="">-- Select Service --</option>
                        {services.map(s => (
                          <option key={s.id} value={s.id}>{s.name} ({s.price})</option>
                        ))}
                      </select>
                    </td>

                    {/* Price (Read Only) */}
                    <td>
                      <input
                        type="number"
                        readOnly
                        className={`${styles.input} ${styles.readOnly}`}
                        value={data.price ?? ''}
                      />
                    </td>

                    {/* Discount Input */}
                    <td>
                      <input
                        type="number"
                        className={styles.input}
                        min="0"
                        value={data.discount_value ?? 0}
                        onChange={(e) => setData('discount_value', Number(e.target.value))}
                      />
                    </td>

                    {/* Tax Percentage Input */}
                    <td>
                      <input
                        type="number"
                        className={styles.input}
                        min="0"
                        max="100"
                        value={data.tax_rate ?? 0}
                        onChange={(e) => setData('tax_rate', Number(e.target.value))}
                      />
                    </td>

                    {/* Tax Value (Calculated Live - Read Only) */}
                    <td>
                      <input
                        type="number"
                        readOnly
                        className={`${styles.input} ${styles.readOnly}`}
                        value={calc.tax_value}
                      />
                    </td>

                    {/* Live Row Total (Read Only) */}
                    <td>
                      <input
                        type="number"
                        readOnly
                        className={`${styles.input} ${styles.readOnly} ${styles.totalInput}`}
                        value={calc.total}
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            {/* Summary Section */}
            <div className={styles.summary}>
              <div className={styles.summaryRow}>
                <span>Subtotal</span>
                <strong>{calc.subtotal} EGP</strong>
              </div>
              <div className={styles.summaryRow}>
                <span>Tax ({data.tax_rate}%)</span>
                <strong>{calc.tax_value} EGP</strong>
              </div>
              <div className={`${styles.summaryRow} ${styles.totalRow}`}>
                <span>Total</span>
                <strong>{calc.total} EGP</strong>
              </div>
            </div>

          </div>

          {/* Form Footer Buttons */}
          <div className={styles.footer}>
            <button type="button" className={styles.cancelBtn} onClick={onSuccess}>
              Cancel
            </button>
            <button type="submit" className={styles.submitBtn} disabled={processing}>
              {processing ? 'Saving...' : 'Save Invoice'}
            </button>
          </div>
        </form>
      </div>
    </>
  );
}

export default InvoiceForm;