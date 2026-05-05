import React, { useState } from 'react';
import { Patient } from './types';
import DeletePatientModal from './DeletePatientModal';

const PatientList: React.FC = () => {
    const [patients, setPatients] = useState<Patient[]>([]);
    const [selectedPatient, setSelectedPatient] = useState<Patient | null>(null);
    const [isDeleteOpen, setIsDeleteOpen] = useState(false);

    return (
        <div className="card">
            <div className="card-header pb-0">
                <div className="d-flex justify-content-between">
                    <button className="btn btn-primary">اضافة مريض جديد</button>
                </div>
            </div>
            <div className="card-body">
                <div className="table-responsive">
                    <table className="table text-md-nowrap" id="example1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم المريض</th>
                                <th>البريد الالكتروني</th>
                                <th>تاريخ الميلاد</th>
                                <th>رقم الهاتف</th>
                                <th>الجنس</th>
                                <th>فصيلة الدم</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            {patients.map((patient, index) => (
                                <tr key={patient.id}>
                                    <td>{index + 1}</td>
                                    <td>{patient.name}</td>
                                    <td>{patient.email}</td>
                                    <td>{patient.Date_Birth}</td>
                                    <td>{patient.Phone}</td>
                                    <td>{patient.Gender === 1 ? 'ذكر' : 'انثي'}</td>
                                    <td>{patient.Blood_Group}</td>
                                    <td>
                                        <button className="btn btn-sm btn-success me-1"><i className="fas fa-edit"></i></button>
                                        <button 
                                            className="btn btn-sm btn-danger"
                                            onClick={() => { setSelectedPatient(patient); setIsDeleteOpen(true); }}
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
            {isDeleteOpen && selectedPatient && (
                <DeletePatientModal 
                    patient={selectedPatient} 
                    onClose={() => setIsDeleteOpen(false)} 
                />
            )}
        </div>
    );
};