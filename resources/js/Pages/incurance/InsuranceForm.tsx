import React, { useState } from 'react';
import { Insurance } from './types';

interface Props {
    initialData?: Insurance;
}

const InsuranceForm: React.FC<Props> = ({ initialData }) => {
    const [formData, setFormData] = useState<Insurance>(initialData || {
        insurance_code: '',
        name: '',
        discount_percentage: '',
        Company_rate: '',
        status: 1,
        notes: ''
    });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const { name, value, type } = e.target;
        const finalValue = type === 'checkbox' ? (e.target as HTMLInputElement).checked ? 1 : 0 : value;
        setFormData({ ...formData, [name]: finalValue });
    };

    return (
        <form className="card-body">
            <div className="row">
                <div className="col-md-6">
                    <label>كود الشركة</label>
                    <input type="text" name="insurance_code" value={formData.insurance_code} onChange={handleChange} className="form-control" />
                </div>
                <div className="col-md-6">
                    <label>اسم الشركة</label>
                    <input type="text" name="name" value={formData.name} onChange={handleChange} className="form-control" />
                </div>
            </div>

            <div className="row mt-3">
                <div className="col-md-6">
                    <label>نسبة الخصم %</label>
                    <input type="number" name="discount_percentage" value={formData.discount_percentage} onChange={handleChange} className="form-control" />
                </div>
                <div className="col-md-6">
                    <label>نسبة تحمل الشركة %</label>
                    <input type="number" name="Company_rate" value={formData.Company_rate} onChange={handleChange} className="form-control" />
                </div>
            </div>

            <div className="mt-3">
                <label>ملاحظات</label>
                <textarea name="notes" rows={5} value={formData.notes} onChange={handleChange} className="form-control"></textarea>
            </div>

            <div className="mt-3 form-check">
                <input 
                    type="checkbox" 
                    name="status" 
                    checked={formData.status === 1} 
                    onChange={handleChange} 
                    className="form-check-input" 
                />
                <label className="form-check-label ms-4">حالة التفعيل</label>
            </div>

            <button type="submit" className="btn btn-success mt-4">حفظ البيانات</button>
        </form>
    );
};