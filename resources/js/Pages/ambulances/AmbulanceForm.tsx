import React, { useState } from 'react';
import { Ambulance } from './types';

interface Props {
    initialData?: Ambulance;
}

const AmbulanceForm: React.FC<Props> = ({ initialData }) => {
    const [formData, setFormData] = useState<Ambulance>(initialData || {
        car_number: '',
        car_model: '',
        car_year_made: '',
        car_type: '1',
        driver_name: '',
        driver_license_number: '',
        driver_phone: '',
        is_available: true,
        notes: ''
    });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
        const { name, value, type } = e.target;
        const finalValue = type === 'checkbox' ? (e.target as HTMLInputElement).checked : value;
        setFormData({ ...formData, [name]: finalValue });
    };

    return (
        <form className="card-body">
            <div className="row">
                <div className="col-md-3">
                    <label>رقم السيارة</label>
                    <input type="text" name="car_number" value={formData.car_number} onChange={handleChange} className="form-control" />
                </div>
                <div className="col-md-3">
                    <label>موديل السيارة</label>
                    <input type="text" name="car_model" value={formData.car_model} onChange={handleChange} className="form-control" />
                </div>
                <div className="col-md-3">
                    <label>سنة الصنع</label>
                    <input type="number" name="car_year_made" value={formData.car_year_made} onChange={handleChange} className="form-control" />
                </div>
                <div className="col-md-3">
                    <label>نوع السيارة</label>
                    <select name="car_type" value={formData.car_type} onChange={handleChange} className="form-control">
                        <option value="1">مملوكة</option>
                        <option value="2">ايجار</option>
                    </select>
                </div>
            </div>

            <div className="row mt-3">
                <div className="col-md-4">
                    <label>اسم السائق</label>
                    <input type="text" name="driver_name" value={formData.driver_name} onChange={handleChange} className="form-control" />
                </div>
                <div className="col-md-4">
                    <label>رقم الرخصة</label>
                    <input type="number" name="driver_license_number" value={formData.driver_license_number} onChange={handleChange} className="form-control" />
                </div>
                <div className="col-md-4">
                    <label>الهاتف</label>
                    <input type="number" name="driver_phone" value={formData.driver_phone} onChange={handleChange} className="form-control" />
                </div>
            </div>

            <div className="mt-3">
                <label>ملاحظات</label>
                <textarea name="notes" rows={4} value={formData.notes} onChange={handleChange} className="form-control"></textarea>
            </div>

            <div className="mt-3 form-check">
                <input 
                    type="checkbox" 
                    name="is_available" 
                    checked={formData.is_available} 
                    onChange={handleChange} 
                    className="form-check-input" 
                />
                <label className="form-check-label ms-4">حالة التفعيل</label>
            </div>

            <button type="submit" className="btn btn-success mt-4">حفظ البيانات</button>
        </form>
    );
};

export default AmbulanceForm;