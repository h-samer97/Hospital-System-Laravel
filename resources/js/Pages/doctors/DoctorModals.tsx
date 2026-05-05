const DoctorModals = ({ type, doctor, selectedIds, onClose }: any) => {
    if (!type) return null;

    return (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title">
                            {type === 'password' && `تغيير كلمة مرور ${doctor?.name}`}
                            {type === 'status' && 'تغيير الحالة'}
                            {type === 'delete' && 'حذف بيانات الطبيب'}
                        </h5>
                        <button onClick={onClose} className="close"><span>&times;</span></button>
                    </div>
                    <div className="modal-body">
                        {type === 'password' && (
                            <div className="form-group">
                                <label>كلمة المرور الجديدة</label>
                                <input type="password" className="form-control" />
                            </div>
                        )}
                        {type === 'status' && ( 
                            <select className="form-control">
                                <option value="1">مفعل</option>
                                <option value="0">غير مفعل</option>
                            </select>
                        )}
                        {type === 'delete' && <p className="text-danger">هل أنت متأكد من حذف الطبيب {doctor?.name}؟</p>}
                        {type === 'bulkDelete' && <p className="text-danger">هل أنت متأكد من حذف {selectedIds.length} من الأطباء؟</p>}
                    </div>
                    <div className="modal-footer">
                        <button onClick={onClose} className="btn btn-secondary">إغلاق</button>
                        <button className="btn btn-primary">تأكيد</button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default DoctorModals;