import React, { useState } from 'react';
import { SingleInvoice } from './InvoiceTypes';

interface Props {
    invoices: SingleInvoice[];
    onEdit: (id: number) => void;
    onDelete: (id: number) => void;
}

const SingleInvoiceTable: React.FC<Props> = ({ invoices, onEdit, onDelete }) => {
    return (
        <div className="table-responsive">
            <table className="table text-md-nowrap text-center" id="example1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الخدمة</th>
                        <th>اسم المريض</th>
                        <th>تاريخ الفاتورة</th>
                        <th>اسم الدكتور</th>
                        <th>القسم</th>
                        <th>سعر الخدمة</th>
                        <th>قيمة الخصم</th>
                        <th>نسبة الضريبة</th>
                        <th>قيمة الضريبة</th>
                        <th>الاجمالي مع الضريبة</th>
                        <th>نوع الفاتورة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    {invoices.map((invoice, index) => (
                        <tr key={invoice.id}>
                            <td>{index + 1}</td>
                            <td>{invoice.service_name}</td>
                            <td>{invoice.patient_name}</td>
                            <td>{invoice.invoice_date}</td>
                            <td>{invoice.doctor_name}</td>
                            <td>{invoice.section_name}</td>
                            <td>{invoice.price.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                            <td>{invoice.discount_value.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                            <td>{invoice.tax_rate}%</td>
                            <td>{invoice.tax_value.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                            <td className="font-weight-bold text-success">
                                {invoice.total_with_tax.toLocaleString(undefined, { minimumFractionDigits: 2 })}
                            </td>
                            <td>
                                <span className={`badge ${invoice.type === 1 ? 'badge-info' : 'badge-warning'}`}>
                                    {invoice.type === 1 ? 'نقدي' : 'اجل'}
                                </span>
                            </td>
                            <td>
                                <button onClick={() => onEdit(invoice.id)} className="btn btn-primary btn-sm me-1">
                                    <i className="fa fa-edit"></i>
                                </button>
                                <button onClick={() => onDelete(invoice.id)} className="btn btn-danger btn-sm">
                                    <i className="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};
export default SingleInvoiceTable;