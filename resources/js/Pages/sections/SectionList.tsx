import React, { useState } from 'react';
import { Section } from './types';
import SectionModal from './SectionModal';
import SectionDeleteModal from './SectionDeleteModal';

const SectionList: React.FC = () => {
    const [sections, setSections] = useState<Section[]>([]); 
    const [activeModal, setActiveModal] = useState<'add' | 'edit' | 'delete' | null>(null);
    const [selectedSection, setSelectedSection] = useState<Section | null>(null);

    const handleAction = (type: 'edit' | 'delete', section: Section) => {
        setSelectedSection(section);
        setActiveModal(type);
    };

    return (
        <div className="card">
            <div className="card-header pb-0">
                <div className="d-flex justify-content-between">
                    <button className="btn btn-primary" onClick={() => setActiveModal('add')}>
                        إضافة قسم جديد
                    </button>
                </div>
            </div>
            <div className="card-body">
                <div className="table-responsive">
                    <table className="table text-md-nowrap" id="example2">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم القسم</th>
                                <th>تاريخ الإنشاء</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            {sections.map((section, index) => (
                                <tr key={section.id}>
                                    <td>{index + 1}</td>
                                    <td>{section.name}</td>
                                    <td>{section.created_at}</td>
                                    <td>
                                        <button className="btn btn-sm btn-info me-1" onClick={() => handleAction('edit', section)}>
                                            <i className="las la-pen"></i>
                                        </button>
                                        <button className="btn btn-sm btn-danger" onClick={() => handleAction('delete', section)}>
                                            <i className="las la-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* التحقق من النوع لفتح الـ Modal المناسب */}
            {(activeModal === 'add' || activeModal === 'edit') && (
                <SectionModal 
                    type={activeModal} 
                    section={selectedSection} 
                    onClose={() => setActiveModal(null)} 
                />
            )}
            {activeModal === 'delete' && selectedSection && (
                <SectionDeleteModal 
                    section={selectedSection} 
                    onClose={() => setActiveModal(null)} 
                />
            )}
        </div>
    );
};