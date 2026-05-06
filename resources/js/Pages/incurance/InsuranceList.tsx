import React from 'react';
import { Link } from '@inertiajs/react';
import SimpleLayout from '../Layout/SimpleLayout';

interface Insurance {
    id: number;
    name: string;
    insurance_code: string;
    discount_percentage: number;
    Company_rate: number;
    status: number;
    created_at: string;
}

const InsuranceList: React.FC<{ insurances?: Insurance[] }> = ({ insurances = [] }) => {
    return (
        <SimpleLayout title="Insurance">
            <div className="mb-6 flex justify-between items-center">
                <h2 className="text-2xl font-bold text-gray-900">Insurance List</h2>
                <Link 
                    href="/insurance/create" 
                    className="btn btn-primary"
                >
                    Add New Insurance
                </Link>
            </div>

            <div className="card">
                <div className="overflow-x-auto">
                    <table className="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Discount %</th>
                                <th>Company Rate %</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {insurances.map((insurance) => (
                                <tr key={insurance.id}>
                                    <td>{insurance.id}</td>
                                    <td>{insurance.insurance_code}</td>
                                    <td className="font-medium">{insurance.name}</td>
                                    <td>{insurance.discount_percentage}%</td>
                                    <td>{insurance.Company_rate}%</td>
                                    <td>
                                        <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                                            insurance.status === 1 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-red-100 text-red-800'
                                        }`}>
                                            {insurance.status === 1 ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td>{insurance.created_at}</td>
                                    <td>
                                        <div className="flex space-x-2">
                                            <Link 
                                                href={`/insurance/${insurance.id}/edit`}
                                                className="btn btn-secondary text-sm"
                                            >
                                                Edit
                                            </Link>
                                            <button 
                                                className="btn btn-danger text-sm"
                                                onClick={() => console.log('Delete insurance:', insurance.id)}
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

export default InsuranceList;