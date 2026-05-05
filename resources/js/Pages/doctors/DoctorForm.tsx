import React, { useState, ChangeEvent } from 'react';
import { Doctor, Section } from './types';

const DoctorForm: React.FC<{ sections: Section[] }> = ({ sections }) => {
    const [doctor, setDoctor] = useState<Doctor>({
        name: '', email: '', phone: '', section_id: '', appointments: [], price: '0.00', status: 1
    });
    const [preview, setPreview] = useState<string | null>(null);

    const handleImageChange = (e: ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setDoctor({ ...doctor, photo: file });
            setPreview(URL.createObjectURL(file)); // معاينة الصورة فوراً
        }
    };

    return (
        <form className="card-body bg-gray-200">
            <div className="row row-xs mg-b-20">
                <div className="col-md-1"><label>الاسم</label></div>
                <div className="col-md-11"><input className="form-control" type="text" name="name" value={doctor.name} /></div>
            </div>

            <div className="row row-xs mg-b-20">
                <div className="col-md-1"><label>القسم</label></div>
                <div className="col-md-11">
                    <select className="form-control" value={doctor.section_id}>
                        <option value="" disabled>------</option>
                        {sections.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
                    </select>
                </div>
            </div>

            <div className="row row-xs mg-b-20">
                <div className="col-md-1"><label>المواعيد</label></div>
                <div className="col-md-11">
                    <select multiple className="form-control">
                        {['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'].map(day => (
                            <option key={day} value={day}>{day}</option>
                        ))}
                    </select>
                </div>
            </div>

            <div className="row row-xs mg-b-20">
                <div className="col-md-1"><label>صورة الطبيب</label></div>
                <div className="col-md-11">
                    <input type="file" accept="image/*" onChange={handleImageChange} />
                    {preview && <img src={preview} style={{ borderRadius: '50%', width: '150px', height: '150px', marginTop: '10px' }} />}
                </div>
            </div>

            <button type="submit" className="btn btn-main-primary">حفظ البيانات</button>
        </form>
    );
};