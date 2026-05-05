import React, { useState } from 'react';
import { Insurance } from './types';
import DeleteInsuranceModal from './DeleteInsuranceModal';

const InsuranceList: React.FC = () => {
    const [insurances, setInsurances] = useState<Insurance[]>([]);
    const [selectedInsurance, setSelectedInsurance] = useState<Insurance | null>(null);
    const [showDeleteModal, setShowDeleteModal] = useState(false);

    return (
        <div className="card">
            <div className="card-header">
                <button className="btn btn-primary">إضافة شركة تأمين</button>
            </div>
            <div className="card-body">
                <div className="table-responsive">
                    <table className="table text-md-nowrap text-center">
                        <thead>
                            <tr className="table-secondary">
                                <th>#</th>
                                <th>كود الشركة</th>
                                <th>اسم الشركة</th>
                                <th>نسبة الخصم %</th>
                                <th>تحمل الشركة %</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            {insurances.map((item, index) => (
                                <tr key={item.id}>
                                    <td>{index + 1}</td>
                                    <td>{item.insurance_code}</td>
                                    <td>{item.name}</td>
                                    <td>{item.discount_percentage}%</td>
                                    <td>{item.Company_rate}%</td>
                                    <td>
                                        <span className={`badge ${item.status === 1 ? 'bg-success' : 'bg-danger'}`}>
                                            {item.status === 1 ? 'مفعل' : 'غير مفعل'}
                                        </span>
                                    </td>
                                    <td>
                                        <button className="btn btn-sm btn-success me-1"><i className="fas fa-edit"></i></button>
                                        <button 
                                            className="btn btn-sm btn-danger"
                                            onClick={() => { setSelectedInsurance(item); setShowDeleteModal(true); }}
                                        >
                                            <i className="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
            {showDeleteModal && selectedInsurance && (
                <DeleteInsuranceModal 
                    insurance={selectedInsurance} 
                    onClose={() => setShowDeleteModal(false)} 
                />
            )}
        </div>
    );
};