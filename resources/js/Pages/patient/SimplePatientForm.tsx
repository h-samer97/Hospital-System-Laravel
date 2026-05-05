import React, { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import SimpleLayout from '../Layout/SimpleLayout';

const SimplePatientForm: React.FC = () => {
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
        address: '',
        date_of_birth: '',
    });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        // Here you would typically make an API call
        console.log('Form data:', formData);
        
        // Redirect after successful submission
        router.visit('/patients');
    };

    return (
        <SimpleLayout title="Add Patient">
            <div className="mb-6">
                <Link href="/patients" className="btn btn-secondary">
                    ← Back to Patients
                </Link>
            </div>

            <div className="card">
                <div className="card-header">
                    <h3 className="text-lg font-medium text-gray-900">Patient Information</h3>
                </div>
                <div className="card-body">
                    <form onSubmit={handleSubmit} className="space-y-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label className="form-label">
                                    Full Name *
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    value={formData.name}
                                    onChange={handleChange}
                                    className="form-input"
                                    required
                                />
                            </div>

                            <div>
                                <label className="form-label">
                                    Email *
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    value={formData.email}
                                    onChange={handleChange}
                                    className="form-input"
                                    required
                                />
                            </div>

                            <div>
                                <label className="form-label">
                                    Phone Number *
                                </label>
                                <input
                                    type="tel"
                                    name="phone"
                                    value={formData.phone}
                                    onChange={handleChange}
                                    className="form-input"
                                    required
                                />
                            </div>

                            <div>
                                <label className="form-label">
                                    Date of Birth
                                </label>
                                <input
                                    type="date"
                                    name="date_of_birth"
                                    value={formData.date_of_birth}
                                    onChange={handleChange}
                                    className="form-input"
                                />
                            </div>
                        </div>

                        <div>
                            <label className="form-label">
                                Address
                            </label>
                            <textarea
                                name="address"
                                value={formData.address}
                                onChange={handleChange}
                                rows={3}
                                className="form-input"
                                placeholder="Enter patient address"
                            />
                        </div>

                        <div className="flex justify-end space-x-3">
                            <Link href="/patients" className="btn btn-secondary">
                                Cancel
                            </Link>
                            <button type="submit" className="btn btn-primary">
                                Save Patient
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </SimpleLayout>
    );
};

export default SimplePatientForm;
