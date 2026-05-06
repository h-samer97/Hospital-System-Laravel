import React from 'react';
import { Link } from '@inertiajs/react';
import SimpleLayout from '../Layout/SimpleLayout';

interface Section {
    id: number;
    name: string;
    created_at: string;
}

const SectionList: React.FC<{ sections?: Section[] }> = ({ sections = [] }) => {
    return (
        <SimpleLayout title="Sections">
            <div className="mb-6 flex justify-between items-center">
                <h2 className="text-2xl font-bold text-gray-900">Sections List</h2>
                <Link 
                    href="/sections/create" 
                    className="btn btn-primary"
                >
                    Add New Section
                </Link>
            </div>

            <div className="card">
                <div className="overflow-x-auto">
                    <table className="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {sections.map((section) => (
                                <tr key={section.id}>
                                    <td>{section.id}</td>
                                    <td className="font-medium">{section.name}</td>
                                    <td>{section.created_at}</td>
                                    <td>
                                        <div className="flex space-x-2">
                                            <Link 
                                                href={`/sections/${section.id}/edit`}
                                                className="btn btn-secondary text-sm"
                                            >
                                                Edit
                                            </Link>
                                            <button 
                                                className="btn btn-danger text-sm"
                                                onClick={() => console.log('Delete section:', section.id)}
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

export default SectionList;