import React, { useState } from "react";
import { Group, Service } from "./types";
import Toast from "@/Components/UI/Toast";
import styles from "./Index.module.css";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head } from "@inertiajs/react";
import GroupFormModal from "./GroupFormModal";
import { timeAgo } from "@/utils/date";
import DeleteModal from "@/Components/UI/DeleteModal";

interface Props {
  groups: Group[];
  services: Service[];
  url_store: string;
}

const GroupsIndex: React.FC<Props> = ({ groups, url_store, services }) => {

    const [addForm, setAddForm] = useState<boolean>(false);
    const [deleteGroup, setDeleteGroup] = useState<Group | null>(null);


  return (
  <DashboardLayout title="Group Services">
    <Head title="Group Services" />
    <Toast />
    
    <div className={styles.page}>
      <div className={styles.header}>
        <div>
          <h1>Group Services</h1>
          <p className={styles.breadcrumb}>Dashboard / Services / Groups</p>
        </div>
        <button className={styles.addBtn} onClick={() => setAddForm(!addForm)}>
            { addForm ? "x Cancel" : "+ New Group" }
        </button>
      </div>

     {addForm && <GroupFormModal urlStore={url_store} services={services} onSuccess={() => setAddForm(false)} />}
     

      {/* Table Section */}
      <div className={styles.card}>
        <div className={styles.tableWrapper}>
          <table className={styles.table}>
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Services</th>
                <th>Subtotal</th>
                <th>Discount</th>
                <th>Tax %</th>
                <th>Total</th>
                <th>Added</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
             
                  {groups.length === 0 ? (
                    <tr>
                      <td colSpan={9} className={styles.empty}>No groups found</td>
                    </tr>
                  ) : (

                      groups.map((group, i) => (

                          <tr key={group.id}>
                      <td>{i + 1}</td>
                      <td>
                        <strong>{group.name}</strong>
                        {group.notes && <p className={styles.notes}>{group.notes}</p>}
                      </td>
                      <td>
                        {group.services.map(s => (
                          <span key={s.id} className={styles.serviceTag}>
                            {s.name} × {s.quantity}
                          </span>
                        ))}
                      </td>
                      <td>{Number(group.subtotal).toLocaleString()} EGP</td>
                      <td>{Number(group.discount).toLocaleString()} EGP</td>
                      <td>{group.tax_percent}%</td>
                      <td className={styles.total}>
                        {Number(group.total).toLocaleString()} EGP
                      </td>
                      <td>{timeAgo(group.created_at, 'en')}</td>
                      <td>
                        <button className={styles.deleteBtn}
                          onClick={() => setDeleteGroup(group)}>
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

    {deleteGroup && (
        <DeleteModal
          name={deleteGroup.name}
          deleteUrl={groups[0]?.urls?.delete || ''}
          onClose={() => setDeleteGroup(null)}
        />
      )}
  </DashboardLayout>
);
};

export default GroupsIndex;