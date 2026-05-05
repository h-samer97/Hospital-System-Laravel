import React, { useState } from 'react';
import { Doctor } from './types';
import DoctorModals from './DoctorModals';

const DoctorList: React.FC = () => {
    const [doctors, setDoctors] = useState<Doctor[]>([]);
    const [selectedIds, setSelectedIds] = useState<number[]>([]);
    const [activeDoctor, setActiveDoctor] = useState<Doctor | null>(null);
    const [modalType, setModalType] = useState<'delete' | 'password' | 'status' | 'bulkDelete' | null>(null);

    const toggleSelect = (id: number) => {
        setSelectedIds(prev => prev.includes(id) ? prev.filter(i => i !== id) : [...prev, id]);
    };

    return (
        <div className="card">
            <div className="card-header pb-0">
                <button className="btn btn-primary">إضافة طبيب</button>
                <button 
                    className="btn btn-danger ms-2" 
                    disabled={selectedIds.length === 0}
                    onClick={() => setModalType('bulkDelete')}
                >
                    حذف المختار
                </button>
            </div>
            <div className="card-body">
                <div className="table-responsive">
                    <table className="table key-buttons text-md-nowrap">
                        <thead>
                            <tr>
                                <th><input type="checkbox" onChange={(e) => {/* منطق تحديد الكل */}} /></th>
                                <th>الاسم</th>
                                <th>القسم</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            {doctors.map((doctor) => (
                                <tr key={doctor.id}>
                                    <td><input type="checkbox" checked={selectedIds.includes(doctor.id!)} onChange={() => toggleSelect(doctor.id!)} /></td>
                                    <td>{doctor.name}</td>
                                    <td>{doctor.section_id}</td>
                                    <td>
                                        <span className={`dot-label bg-${doctor.status === 1 ? 'success' : 'danger'}`}></span>
                                        {doctor.status === 1 ? 'مفعل' : 'غير مفعل'}
                                    </td>
                                    <td>
                                        <div className="dropdown">
                                            <button className="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                العمليات
                                            </button>
                                            <ul className="dropdown-menu">
                                                <li onClick={() => {/* Navigate to edit */}} className="dropdown-item">تعديل البيانات</li>
                                                <li onClick={() => { setActiveDoctor(doctor); setModalType('password'); }} className="dropdown-item">تغيير كلمة المرور</li>
                                                <li onClick={() => { setActiveDoctor(doctor); setModalType('status'); }} className="dropdown-item">تغيير الحالة</li>
                                                <li onClick={() => { setActiveDoctor(doctor); setModalType('delete'); }} className="dropdown-item text-danger">حذف البيانات</li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
            <DoctorModals type={modalType} doctor={activeDoctor} selectedIds={selectedIds} onClose={() => setModalType(null)} />
        </div>
    );
};