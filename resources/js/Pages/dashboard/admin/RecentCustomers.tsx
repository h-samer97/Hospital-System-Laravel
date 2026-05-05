import React from 'react';
import { Customer } from './types';

const RecentCustomers: React.FC = () => {
    const customers: Customer[] = [
        { id: '#1234', name: 'Samantha Melon', status: 'Paid', img: 'faces/3.jpg' },
        { id: '#1235', name: 'Jimmy Changa', status: 'Pending', img: 'faces/11.jpg' },
        // أضف باقي العملاء هنا بنفس التنسيق
    ];

    return (
        <div className="card">
            <div className="card-header pb-1">
                <h3 className="card-title mb-2">العملاء الأخيرون</h3>
                <p className="tx-12 mb-0 text-muted">قائمة بأحدث العمليات الشرائية والعملاء الجدد.</p>
            </div>
            <div className="card-body p-0 customers mt-1">
                <div className="list-group list-group-flush">
                    {customers.map((customer) => (
                        <div key={customer.id} className="list-group-item list-group-item-action">
                            <div className="media mt-0">
                                <img className="avatar-lg rounded-circle ml-3 my-auto" src={`/assets/img/${customer.img}`} alt={customer.name} />
                                <div className="media-body">
                                    <div className="d-flex align-items-center">
                                        <div className="mt-0">
                                            <h5 className="mb-1 tx-15">{customer.name}</h5>
                                            <p className="mb-0 tx-13 text-muted">
                                                ID: {customer.id} 
                                                <span className={`ml-2 ${customer.status === 'Paid' ? 'text-success' : 'text-danger'}`}>
                                                    {customer.status === 'Paid' ? 'تم الدفع' : 'قيد الانتظار'}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
};

export default RecentCustomers;