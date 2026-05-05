import React from 'react';
import { Patient } from './types';

const DeletePatientModal: React.FC<{ patient: Patient; onClose: () => void }> = ({ patient, onClose }) => {
    return (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title">حذف بيانات مريض</h5>
                        <button onClick={onClose} className="close"><span>&times;</span></button>
                    </div>
                    <div className="modal-body text-center">
                        <p className="h5 text-danger">هل انت متاكد من حذف بيانات المريض ؟</p>
                        <input type="text" className="form-control text-center" readOnly value={patient.name} />
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

export default DeletePatientModal;