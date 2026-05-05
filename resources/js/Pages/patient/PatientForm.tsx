import React, { useState } from 'react';
import { Patient } from './types';

interface Props {
    initialData?: Patient;
}

const PatientForm: React.FC<Props> = ({ initialData }) => {
    const [formData, setFormData] = useState<Patient>(initialData || {
        name: '',
        email: '',
        Date_Birth: '',
        Phone: '',
        Gender: 1,
        Blood_Group: '',
        Address: ''
    });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
    };

    return (
        <div className="card-body">
            <form>
                <div className="row">
                    <div className="col-md-4 mb-3">
                        <label>اسم المريض</label>
                        <input type="text" name="name" value={formData.name} onChange={handleChange} className="form-control" required />
                    </div>
                    <div className="col-md-4 mb-3">
                        <label>البريد الالكتروني</label>
                        <input type="email" name="email" value={formData.email} onChange={handleChange} className="form-control" required />
                    </div>
                    <div className="col-md-4 mb-3">
                        <label>تاريخ الميلاد</label>
                        <input type="date" name="Date_Birth" value={formData.Date_Birth} onChange={handleChange} className="form-control" required />
                    </div>
                </div>

                <div className="row mt-3">
                    <div className="col-md-4 mb-3">
                        <label>رقم الهاتف</label>
                        <input type="number" name="Phone" value={formData.Phone} onChange={handleChange} className="form-control" required />
                    </div>
                    <div className="col-md-4 mb-3">
                        <label>الجنس</label>
                        <select name="Gender" value={formData.Gender} onChange={handleChange} className="form-control" required>
                            <option value={1}>ذكر</option>
                            <option value={2}>انثي</option>
                        </select>
                    </div>
                    <div className="col-md-4 mb-3">
                        <label>فصيلة الدم</label>
                        <select name="Blood_Group" value={formData.Blood_Group} onChange={handleChange} className="form-control" required>
                            <option value="">-- اختار --</option>
                            {['O-', 'O+', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'].map(bg => (
                                <option key={bg} value={bg}>{bg}</option>
                            ))}
                        </select>
                    </div>
                </div>

                <div className="row mt-3">
                    <div className="col-12">
                        <label>العنوان</label>
                        <textarea name="Address" rows={5} value={formData.Address} onChange={handleChange} className="form-control"></textarea>
                    </div>
                </div>

                <div className="mt-4">
                    <button type="submit" className="btn btn-success">حفظ البيانات</button>
                </div>
            </form>
        </div>
    );
};