import React from 'react';
import { Ambulance } from './types';

interface DeleteProps {
    ambulance: Ambulance;
    onClose: () => void;
}

const DeleteAmbulanceModal: React.FC<DeleteProps> = ({ ambulance, onClose }) => {
    return (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title">حذف بيانات سيارة اسعاف</h5>
                        <button onClick={onClose} className="close"><span>&times;</span></button>
                    </div>
                    <div className="modal-body text-center">
                        <p className="h5 text-danger">هل أنت متأكد من حذف بيانات السيارة رقم؟</p>
                        <p className="font-weight-bold">{ambulance.car_number}</p>
                    </div>
                    <div className="modal-footer">
                        <button onClick={onClose} className="btn btn-secondary">إلغاء</button>
                        <button className="btn btn-danger">تأكيد الحذف</button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default DeleteAmbulanceModal;