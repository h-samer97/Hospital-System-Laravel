
import { useState } from "react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import Edit from "./Edit";
import { Services } from "./types";
import styles from "./Index.module.css";
import { timeAgo } from "@/utils/date";
import DeleteModal from "@/Components/UI/DeleteModal";
import ServiceFormModal from "./ServiceFormModal";


interface Props {
    services: Services[];
    url_store: string;
    url_delete: string;
}


const ServicesIndex: React.FC<Props> = ({services, url_store}: Props) => {

    const [edit, setFormEdit] = useState<boolean>(false);
    const [del, setFormDelete] = useState<boolean>(false);
    const [add, setFormAdd] = useState<boolean>(false);
    const [search, setSearch] = useState<string>('');
    const [serviceToDelete, setServiceToDelete] = useState<Services | null>(null);
    const [editService, setEditService] = useState<Services | null>(null);

    const filtered = services.filter(serv => serv.name.toLowerCase().includes(search.toLocaleLowerCase()));

    return (
        <DashboardLayout title="Services">
          <div className={styles.page}>
            <div className={styles.header}>
              <div>
                <h1>Single Services</h1>
                <p className={styles.breadcrumb}>Dashboard / Services</p>
              </div>
              <button className={styles.addBtn} onClick={(e) => setFormAdd(true)}>
                + Add Service
              </button>
            </div>

            <div className={styles.card}>
              <div className={styles.toolbar}>
                <input type="text" placeholder="Search services..." className={styles.searchInput} onChange={(e) => setSearch(e.target.value)}/>
                <span className={styles.count}> {filtered.length} services</span>
              </div>

              <div className={styles.tableWrapper}>
                <table className={styles.table}>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Price</th>
                      <th>Status</th>
                      <th>Added</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                   
                    {filtered.length === 0 ? (
                    
                        <tr>
                            <td colSpan={7} className={styles.empty}>No services found</td>
                        </tr>
                ) : (


                   filtered.map((serv, i) => (
                        <tr key={serv.id}>
                          <td>{i + 1}</td>
                          <td>{serv.name}</td>
                          <td>{
                                serv.description ? serv.description.slice(0, 30) : '-'
                            }</td>
                          <td>${Number(serv.price)}</td>
                          <td>
                                <span className={serv.is_active ? styles.badgeActive : styles.badgeInactive}>
                                <span className={styles.dot} />
                                {serv.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </td>
                          <td title={new Date(serv.created_at).toLocaleDateString()}>
                        {timeAgo(serv.created_at, 'en')}
                        </td>
                          <td>
                            <button className={styles.editBtn} onClick={() => setEditService(serv)}>Edit</button>
                            <button className={styles.deleteBtn} onClick={() => { setServiceToDelete(serv); setFormDelete(true); }}>Delete</button>
                          </td>
                        </tr>
                    ))


                )}


                  </tbody>
                </table>
              </div>
            </div>
          </div>

          {del && serviceToDelete && (
            <DeleteModal 
              name={serviceToDelete.name} 
              deleteUrl={serviceToDelete.url_delete} 
              onClose={() => { setFormDelete(false); setServiceToDelete(null); }} 
            />
          )}
           {/* Modals */}
      {add && (
        <ServiceFormModal mode="add" storeUrl={url_store}
          onClose={() => setFormAdd(false)} />
      )}
      {editService && (
        <ServiceFormModal mode="edit" service={editService}
          storeUrl={editService.url_update}
          onClose={() => setEditService(null)} />
      )}

        </DashboardLayout>
    );
};

export default ServicesIndex;