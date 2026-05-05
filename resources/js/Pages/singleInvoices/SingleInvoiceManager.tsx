import React, { useState } from 'react';
import SingleInvoiceTable from './SingleInvoiceTable';
import { SingleInvoice } from './InvoiceTypes';

const SingleInvoiceManager: React.FC = () => {
    const [showForm, setShowForm] = useState(false); // للتبديل بين الجدول والنموذج
    const [invoices, setInvoices] = useState<SingleInvoice[]>([]); // ستجلب هذه البيانات من API لاحقاً

    return (
        <div className="card">
            <div className="card-header pb-0">
                <div className="d-flex justify-content-between">
                    <h4 className="card-title mg-b-0">فاتورة خدمة مفردة</h4>
                    {!showForm && (
                        <button 
                            className="btn btn-primary" 
                            onClick={() => setShowForm(true)}
                        >
                            اضافة فاتورة جديدة
                        </button>
                    )}
                </div>
            </div>
            <div className="card-body">
                {showForm ? (
                    <div>
                        {/* هنا يتم استدعاء مكون النموذج (Form) */}
                        <h5>نموذج إضافة فاتورة جديدة</h5>
                        <button className="btn btn-secondary mt-3" onClick={() => setShowForm(false)}>
                            العودة للجدول
                        </button>
                    </div>
                ) : (
                    <SingleInvoiceTable 
                        invoices={invoices} 
                        onEdit={(id) => console.log('Edit', id)} 
                        onDelete={(id) => console.log('Delete', id)} 
                    />
                )}
            </div>
        </div>
    );
};

export default SingleInvoiceManager;