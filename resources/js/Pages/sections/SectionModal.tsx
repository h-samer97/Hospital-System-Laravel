import React, { useState, useEffect } from 'react';
import { Section } from './types';

interface Props {
    type: 'add' | 'edit';
    section: Section | null;
    onClose: () => void;
}

const SectionModal: React.FC<Props> = ({ type, section, onClose }) => {
    const [name, setName] = useState('');

    useEffect(() => {
        if (type === 'edit' && section) {
            setName(section.name);
        }
    }, [type, section]);

    return (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title">
                            {type === 'add' ? 'إضافة قسم' : 'تعديل قسم'}
                        </h5>
                        <button onClick={onClose} className="close"><span>&times;</span></button>
                    </div>
                    <form>
                        <div className="modal-body">
                            <label>اسم القسم</label>
                            <input 
                                type="text" 
                                className="form-control" 
                                value={name} 
                                onChange={(e) => setName(e.target.value)} 
                            />
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-secondary" onClick={onClose}>إغلاق</button>
                            <button type="submit" className="btn btn-primary">تأكيد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default SectionModal;