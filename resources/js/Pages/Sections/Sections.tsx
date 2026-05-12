import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Toast from '@/Components/UI/Toast';
import SectionFormModal from './SectionFormModal';
import DeleteModal from './DeleteModal';
import styles from './Index.module.css';
import type { Section } from './types';

interface Props {
    sections: Section[];    
}
export default function({sections}: Props) {
   
     const [showAddModal, setShowAddModal]       = useState(false);
    const [editSection, setEditSection]         = useState<Section | null>(null);
    const [deleteSection, setDeleteSection]     = useState<Section | null>(null);

    // ---- بحث بسيط في الجدول ----
    const [search, setSearch] = useState('');
    const filtered = sections.filter(s =>
        s.name?.includes(search)
    );

     return (
    <DashboardLayout title="إدارة الأقسام">
      <Head title="إدارة الأقسام" />
      <Toast />

      <div className={styles.page}>

        {/* Header */}
        <div className={styles.header}>
          <div>
            <h1>📋 الأقسام</h1>
            <p className={styles.breadcrumb}>لوحة التحكم / عرض الكل</p>
          </div>
          <button
            className={styles.addBtn}
            onClick={() => setShowAddModal(true)}
          >
            ➕ إضافة قسم
          </button>
        </div>

        {/* الجدول */}
        <div className={styles.card}>
          {/* شريط البحث */}
          <div className={styles.toolbar}>
            <input
              type="text"
              placeholder="بحث..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className={styles.searchInput}
            />
            <span className={styles.count}>{filtered.length} قسم</span>
          </div>

          <div className={styles.tableWrapper}>
            <table className={styles.table}>
              <thead>
                <tr>
                  <th>#</th>
                  <th>الاسم</th>
                  <th>تاريخ الإضافة</th>
                  <th>الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                {filtered.length === 0 ? (
                  <tr>
                    <td colSpan={4} className={styles.empty}>
                      لا توجد أقسام
                    </td>
                  </tr>
                ) : (
                  filtered.map((section, index) => (
                    <tr key={section.id}>
                      <td>{index + 1}</td>
                      <td>{section.name}</td>
                      <td>{new Date(section.created_at).toLocaleDateString('ar-EG')}</td>
                      <td className={styles.actions}>
                        <button
                          className={styles.editBtn}
                          onClick={() => setEditSection(section)}
                          title="تعديل"
                        >
                          ✏️
                        </button>
                        <button
                          className={styles.deleteBtn}
                          onClick={() => setDeleteSection(section)}
                          title="حذف"
                        >
                          🗑️
                        </button>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>

      </div>

      {/* Modals */}
      {showAddModal && (
        <SectionFormModal
          mode="add"
          onClose={() => setShowAddModal(false)}
        />
      )}

      {editSection && (
        <SectionFormModal
          mode="edit"
          section={editSection}
          onClose={() => setEditSection(null)}
        />
      )}

      {deleteSection && (
        <DeleteModal
          section={deleteSection}
          onClose={() => setDeleteSection(null)}
        />
      )}

    </DashboardLayout>
  );

}
    
