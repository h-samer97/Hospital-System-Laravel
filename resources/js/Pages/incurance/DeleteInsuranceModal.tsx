import React from 'react';
import { Insurance } from './types';

interface DeleteProps {
    insurance: Insurance;
    onClose: () => void;
}

const DeleteInsuranceModal: React.FC<DeleteProps> = ({ insurance, onClose }) => {
    return (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title">حذف شركة تأمين</h5>
                        <button onClick={onClose} className="close"><span>&times;</span></button>
                    </div>
                    <div className="modal-body text-center">
                        <p className="h5 text-danger">هل أنت متأكد من عملية الحذف؟</p>
                        <p className="font-weight-bold">{insurance.name}</p>
                    </div>
                    <div className="modal-footer">
                        <button onClick={onClose} className="btn btn-secondary">إغلاق</button>
                        <button className="btn btn-danger">تأكيد الحذف</button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default DeleteInsuranceModal;