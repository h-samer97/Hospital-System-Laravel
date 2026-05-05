import React, { useState } from 'react';
import { Ambulance } from './types';
import DeleteAmbulanceModal from './DeleteAmbulanceModal';

const AmbulanceList: React.FC = () => {
    const [ambulances, setAmbulances] = useState<Ambulance[]>([]); // يتم جلبها عبر Axios لاحقاً
    const [selectedAmbulance, setSelectedAmbulance] = useState<Ambulance | null>(null);
    const [showDeleteModal, setShowDeleteModal] = useState(false);

    const openDeleteModal = (ambulance: Ambulance) => {
        setSelectedAmbulance(ambulance);
        setShowDeleteModal(true);
    };

    return (
        <div className="card">
            <div className="card-header pb-0">
                <div className="d-flex justify-content-between">
                    <h4 className="card-title mg-b-0">سيارات الإسعاف</h4>
                    <button className="btn btn-primary">إضافة سيارة جديدة</button>
                </div>
            </div>
            <div className="card-body">
                <div className="table-responsive">
                    <table className="table text-md-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم السيارة</th>
                                <th>موديل السيارة</th>
                                <th>نوع السيارة</th>
                                <th>اسم السائق</th>
                                <th>حالة السيارة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            {ambulances.map((item, index) => (
                                <tr key={item.id}>
                                    <td>{index + 1}</td>
                                    <td>{item.car_number}</td>
                                    <td>{item.car_model}</td>
                                    <td>{item.car_type === '1' ? 'مملوكة' : 'ايجار'}</td>
                                    <td>{item.driver_name}</td>
                                    <td>
                                        <span className={`badge ${item.is_available ? 'badge-success' : 'badge-danger'}`}>
                                            {item.is_available ? 'مفعلة' : 'غير مفعلة'}
                                        </span>
                                    </td>
                                    <td>
                                        <button className="btn btn-sm btn-info me-1"><i className="fas fa-edit"></i></button>
                                        <button 
                                            className="btn btn-sm btn-danger"
                                            onClick={() => openDeleteModal(item)}
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
            {showDeleteModal && selectedAmbulance && (
                <DeleteAmbulanceModal 
                    ambulance={selectedAmbulance} 
                    onClose={() => setShowDeleteModal(false)} 
                />
            )}
        </div>
    );
};

export default AmbulanceList;