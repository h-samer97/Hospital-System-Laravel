import React from 'react';
import { Link } from '@inertiajs/react';
import SimpleLayout from '../Layout/SimpleLayout';

interface SingleInvoice {
    id: number;
    invoice_number: string;
    patient_name: string;
    doctor_name: string;
    service_name: string;
    price: number;
    created_at: string;
}

const SingleInvoiceManager: React.FC<{ invoices?: SingleInvoice[] }> = ({ invoices = [] }) => {
    return (
        <SimpleLayout title="Single Invoices">
            <div className="mb-6 flex justify-between items-center">
                <h2 className="text-2xl font-bold text-gray-900">Single Invoices</h2>
                <Link 
                    href="/single_invoices/create" 
                    className="btn btn-primary"
                >
                    Add New Invoice
                </Link>
            </div>

            <div className="card">
                <div className="overflow-x-auto">
                    <table className="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Invoice Number</th>
                                <th>Patient Name</th>
                                <th>Doctor Name</th>
                                <th>Service Name</th>
                                <th>Price</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {invoices.map((invoice) => (
                                <tr key={invoice.id}>
                                    <td>{invoice.id}</td>
                                    <td className="font-medium">{invoice.invoice_number}</td>
                                    <td>{invoice.patient_name}</td>
                                    <td>{invoice.doctor_name}</td>
                                    <td>{invoice.service_name}</td>
                                    <td>${invoice.price}</td>
                                    <td>{invoice.created_at}</td>
                                    <td>
                                        <div className="flex space-x-2">
                                            <Link 
                                                href={`/single_invoices/${invoice.id}/edit`}
                                                className="btn btn-secondary text-sm"
                                            >
                                                Edit
                                            </Link>
                                            <button 
                                                className="btn btn-danger text-sm"
                                                onClick={() => console.log('Delete invoice:', invoice.id)}
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </SimpleLayout>
    );
};

export default SingleInvoiceManager;