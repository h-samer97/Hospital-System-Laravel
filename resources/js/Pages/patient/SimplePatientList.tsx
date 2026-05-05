import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import SimpleLayout from '../Layout/SimpleLayout';

interface Patient {
    id: number;
    name: string;
    email: string;
    phone: string;
    created_at: string;
}

const SimplePatientList: React.FC = () => {
    const [patients] = useState<Patient[]>([
        { id: 1, name: 'Ahmed Mohamed', email: 'ahmed@example.com', phone: '01234567890', created_at: '2024-01-15' },
        { id: 2, name: 'Fatima Ali', email: 'fatima@example.com', phone: '01123456789', created_at: '2024-01-16' },
        { id: 3, name: 'Mohammed Hassan', email: 'mohammed@example.com', phone: '01098765432', created_at: '2024-01-17' },
    ]);

    return (
        <SimpleLayout title="Patients">
            <div className="mb-6 flex justify-between items-center">
                <h2 className="text-2xl font-bold text-gray-900">Patients List</h2>
                <Link 
                    href="/patients/create" 
                    className="btn btn-primary"
                >
                    Add New Patient
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
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {patients.map((patient) => (
                                <tr key={patient.id}>
                                    <td>{patient.id}</td>
                                    <td className="font-medium">{patient.name}</td>
                                    <td>{patient.email}</td>
                                    <td>{patient.phone}</td>
                                    <td>{patient.created_at}</td>
                                    <td>
                                        <div className="flex space-x-2">
                                            <Link 
                                                href={`/patients/${patient.id}/edit`}
                                                className="btn btn-secondary text-sm"
                                            >
                                                Edit
                                            </Link>
                                            <button 
                                                className="btn btn-danger text-sm"
                                                onClick={() => console.log('Delete patient:', patient.id)}
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

export default SimplePatientList;
