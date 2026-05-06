import React from 'react';
import { Link } from '@inertiajs/react';
import SimpleLayout from '../Layout/SimpleLayout';

interface Doctor {
    id: number;
    name: string;
    email: string;
    phone: string;
    section_id: number;
    status: number;
    created_at: string;
}

const DoctorList: React.FC<{ doctors?: Doctor[] }> = ({ doctors = [] }) => {
    return (
        <SimpleLayout title="Doctors">
            <div className="mb-6 flex justify-between items-center">
                <h2 className="text-2xl font-bold text-gray-900">Doctors List</h2>
                <Link 
                    href="/doctors/create" 
                    className="btn btn-primary"
                >
                    Add New Doctor
                </Link>
            </div>

            <div className="card">
                <div className="overflow-x-auto">
                    <table className="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {doctors.map((doctor) => (
                                <tr key={doctor.id}>
                                    <td>{doctor.id}</td>
                                    <td className="font-medium">{doctor.name}</td>
                                    <td>{doctor.email}</td>
                                    <td>{doctor.phone}</td>
                                    <td>{doctor.section_id}</td>
                                    <td>
                                        <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                                            doctor.status === 1 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-red-100 text-red-800'
                                        }`}>
                                            {doctor.status === 1 ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td>{doctor.created_at}</td>
                                    <td>
                                        <div className="flex space-x-2">
                                            <Link 
                                                href={`/doctors/${doctor.id}/edit`}
                                                className="btn btn-secondary text-sm"
                                            >
                                                Edit
                                            </Link>
                                            <button 
                                                className="btn btn-danger text-sm"
                                                onClick={() => console.log('Delete doctor:', doctor.id)}
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </SimpleLayout>
    );
};

export default DoctorList;