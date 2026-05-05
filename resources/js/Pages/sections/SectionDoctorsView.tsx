import React from 'react';
import { Doctor } from './types';

const SectionDoctorsView: React.FC<{ doctors: Doctor[], sectionName: string }> = ({ doctors, sectionName }) => {
    return (
        <div className="card">
            <div className="card-header">
                <h4 className="card-title">{sectionName} / أطباء القسم</h4>
            </div>
            <div className="card-body">
                <div className="table-responsive">
                    <table className="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>البريد</th>
                                <th>الهاتف</th>
                                <th>المواعيد</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            {doctors.map((doctor, index) => (
                                <tr key={doctor.id}>
                                    <td>{index + 1}</td>
                                    <td>{doctor.name}</td>
                                    <td>{doctor.email}</td>
                                    <td>{doctor.phone}</td>
                                    <td>
                                        {doctor.appointments.map(app => (
                                            <span key={app.id} className="badge badge-light me-1">{app.name}</span>
                                        ))}
                                    </td>
                                    <td>
                                        <div className={`dot-label bg-${doctor.status === 1 ? 'success' : 'danger'}`}></div>
                                        {doctor.status === 1 ? 'مفعل' : 'غير مفعل'}
                                    </td>
                                    <td>
                                        <div className="dropdown">
                                            <button className="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                العمليات
                                            </button>
                                            <ul className="dropdown-menu">
                                                <li className="dropdown-item">تعديل</li>
                                                <li className="dropdown-item">تغيير الحالة</li>
                                                <li className="dropdown-item text-danger">حذف</li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
};