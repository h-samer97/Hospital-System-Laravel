import React from 'react';
import { Link } from '@inertiajs/react';
import SimpleLayout from '../Layout/SimpleLayout';

interface Ambulance {
    id: number;
    name: string;
    number: string;
    phone: string;
    incurance_id: number;
    created_at: string;
}

const AmbulanceList: React.FC<{ ambulances?: Ambulance[] }> = ({ ambulances = [] }) => {
    return (
        <SimpleLayout title="Ambulances">
            <div className="mb-6 flex justify-between items-center">
                <h2 className="text-2xl font-bold text-gray-900">Ambulances List</h2>
                <Link 
                    href="/ambulance/create" 
                    className="btn btn-primary"
                >
                    Add New Ambulance
                </Link>
            </div>

            <div className="card">
                <div className="overflow-x-auto">
                    <table className="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Number</th>
                                <th>Phone</th>
                                <th>Insurance ID</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {ambulances.map((ambulance) => (
                                <tr key={ambulance.id}>
                                    <td>{ambulance.id}</td>
                                    <td className="font-medium">{ambulance.name}</td>
                                    <td>{ambulance.number}</td>
                                    <td>{ambulance.phone}</td>
                                    <td>{ambulance.incurance_id}</td>
                                    <td>{ambulance.created_at}</td>
                                    <td>
                                        <div className="flex space-x-2">
                                            <Link 
                                                href={`/ambulance/${ambulance.id}/edit`}
                                                className="btn btn-secondary text-sm"
                                            >
                                                Edit
                                            </Link>
                                            <button 
                                                className="btn btn-danger text-sm"
                                                onClick={() => console.log('Delete ambulance:', ambulance.id)}
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

export default AmbulanceList;