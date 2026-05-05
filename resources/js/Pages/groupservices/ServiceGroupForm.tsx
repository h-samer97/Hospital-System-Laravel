import React, { useState, useMemo } from 'react';
import { ServiceItem, ServiceGroup } from './types';

const ServiceGroupForm: React.FC = () => {
    const [group, setGroup] = useState<ServiceGroup>({
        name_group: '',
        notes: '',
        items: [],
        discount_value: 0, // قيمة الخصم
        taxes: 0 // نسبة الضريبة
    });

    // إضافة خدمة فرعية جديدة
    const addService = () => {
        const newItem: ServiceItem = {
            service_id: '',
            service_name: '',
            service_price: 0,
            quantity: 1,
            is_saved: false
        };
        setGroup({ ...group, items: [...group.items, newItem] });
    };

    // حساب الإجماليات (تلقائياً عند تغيير أي قيمة)
    const subtotal = useMemo(() => {
        return group.items.reduce((sum, item) => sum + (item.service_price * item.quantity), 0);
    }, [group.items]);

    const total = useMemo(() => {
        const afterDiscount = subtotal - group.discount_value;
        const taxAmount = afterDiscount * (group.taxes / 100);
        return afterDiscount + taxAmount;
    }, [subtotal, group.discount_value, group.taxes]);

    return (
        <div className="card">
            <div className="card-body">
                {/* بيانات المجموعة الأساسية */}
                <div className="row mb-3">
                    <div className="col-md-12">
                        <label>اسم المجموعة</label>
                        <input 
                            type="text" 
                            className="form-control"
                            value={group.name_group}
                            onChange={(e) => setGroup({...group, name_group: e.target.value})}
                        />
                    </div>
                </div>

                {/* جدول الخدمات المتضمنة */}
                <div className="table-responsive mt-4">
                    <table className="table table-bordered text-center">
                        <thead className="table-primary">
                            <tr>
                                <th>اسم الخدمة</th>
                                <th>العدد</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            {group.items.map((item, index) => (
                                <tr key={index}>
                                    <td>
                                        <select 
                                            className="form-control"
                                            value={item.service_id}
                                            onChange={(e) => {
                                                const newItems = [...group.items];
                                                newItems[index].service_id = e.target.value;
                                                // لنفترض أنك تجلب السعر هنا من قائمة الخدمات
                                                newItems[index].service_price = 100; 
                                                setGroup({...group, items: newItems});
                                            }}
                                        >
                                            <option value="">-- اختر الخدمة --</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input 
                                            type="number" 
                                            className="form-control"
                                            value={item.quantity}
                                            onChange={(e) => {
                                                const newItems = [...group.items];
                                                newItems[index].quantity = parseInt(e.target.value);
                                                setGroup({...group, items: newItems});
                                            }}
                                        />
                                    </td>
                                    <td>
                                        <button className="btn btn-sm btn-outline-danger" onClick={() => {
                                            const newItems = group.items.filter((_, i) => i !== index);
                                            setGroup({...group, items: newItems});
                                        }}>
                                            <i className="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                    <button className="btn btn-primary btn-sm mb-3" onClick={addService}>
                        <i className="fa fa-plus"></i> إضافة خدمة فرعية
                    </button>
                </div>

                {/* الحسابات المالية */}
                <div className="row mt-4">
                    <div className="col-lg-4 ms-auto">
                        <table className="table table-sm">
                            <tr>
                                <td className="text-danger">الإجمالي الفرعي</td>
                                <td className="text-left font-weight-bold">{subtotal.toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td className="text-danger">قيمة الخصم</td>
                                <td>
                                    <input 
                                        type="number" 
                                        className="form-control form-control-sm"
                                        value={group.discount_value}
                                        onChange={(e) => setGroup({...group, discount_value: parseFloat(e.target.value) || 0})}
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td className="text-danger">نسبة الضريبة (%)</td>
                                <td>
                                    <input 
                                        type="number" 
                                        className="form-control form-control-sm"
                                        value={group.taxes}
                                        onChange={(e) => setGroup({...group, taxes: parseFloat(e.target.value) || 0})}
                                    />
                                </td>
                            </tr>
                            <tr className="bg-light">
                                <td className="font-weight-bold text-primary">الإجمالي النهائي</td>
                                <td className="text-left font-weight-bold text-primary">{total.toFixed(2)}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div className="modal-footer mt-3">
                    <button className="btn btn-success px-5" onClick={() => console.log(group)}>
                        <i className="fa fa-save"></i> حفظ كافة البيانات
                    </button>
                </div>
            </div>
        </div>
    );
};