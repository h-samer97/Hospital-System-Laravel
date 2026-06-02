import { insurances } from "./types";
import styles from "./Index.module.css";
import { useState, type FC } from "react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head } from "@inertiajs/react";
import FormModal from "./InsuranceFormModal";
import { timeAgo } from "@/utils/date";
import { DeleteIcon, Edit3Icon, EditIcon } from "lucide-react";
import DeleteModal from "@/Components/UI/DeleteModal";

interface Props {
  insurances: insurances[];
  url_store: string;
}
interface ModalState {
  insurance: insurances | null;
  mode: "edit" | "add";
}


const IndexInsurances: FC<Props> = ({ insurances, url_store }) => {

  const [deleteForm, setDeleteForm] = useState<insurances | false>(false);
  const [modal, setModal] = useState<ModalState | null>(null);
  const [search, setSearch] = useState<string>('');

  // includes: true || false
  const filtered = insurances.filter(i => i.name.toLowerCase().includes(search.toLocaleLowerCase()));

  return (


    <DashboardLayout title="Insurances - HMS">
      <Head title="Insurances" />



      <div className={styles.page}>
        <div className={styles.header}>
          <div>
            <h1>Insurances</h1>
            <p className={styles.breadcrumb}>Dashboard / Insurances</p>
          </div>
          <button className={styles.addBtn} onClick={() => setModal({ mode: "add", insurance: null })}>+ Add Insurance</button>
        </div>

        <div className={styles.card}>
          <div className={styles.toolbar}>
            <input type="text" placeholder="Search by name or code..." className={styles.searchInput}
              value={search} onChange={(e) => setSearch(e.target.value)}
            />
            <span className={styles.count}>{filtered.length} insurances</span>
          </div>

          <div className={styles.tableWrapper}>
            <table className={styles.table}>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Code</th>
                  <th>Discount %</th>
                  <th>Company Rate %</th>
                  <th>Notes</th>
                  <th>Status</th>
                  <th>Added</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>

                {filtered.length === 0 ? (
                  <tr>
                    <td colSpan={9} className={styles.empty}>No insurances found</td>
                  </tr>) : (

                  filtered.map((insurance, i) => (

                    <tr key={insurance.id}>
                      <td>
                        {i + 1}
                      </td>
                      <td>
                        {insurance.name}
                      </td>
                      <td>
                        <span className={styles.code}>{insurance.insurance_code}</span>
                      </td>
                      <td>
                        {insurance.discount_percentage}%
                      </td>
                      <td>
                        {insurance.company_rate}
                      </td>
                      <td>
                        {insurance.note?.slice(0, 30) ?? 'None'}
                      </td>
                      <td>
                        <span className={insurance.is_active ? styles.badgeActive : styles.badgeInactive}>
                          <span className={styles.dot} />
                          {insurance.is_active ? 'Active' : 'Inactive'}
                        </span>
                      </td>
                      <td title={new Date(insurance.created_at).toLocaleDateString()}>
                        {timeAgo(insurance.created_at, 'en')}
                      </td>

                      <td>
                        <button className={styles.editBtn} onClick={() => setModal({ mode: "edit", insurance })}>
                          <EditIcon />
                        </button>
                        <button className={styles.deleteBtn}
                          onClick={() => setDeleteForm(insurance)}>
                          <DeleteIcon />
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
      {modal && (
        <FormModal
          mode={modal.mode}
          insurance={modal.insurance ?? undefined}
          url_store={
            modal.mode === 'add'
              ? url_store
              : modal.insurance?.urls.update ?? url_store
          }
          onClose={() => setModal(null)}
        />
      )}

      {deleteForm && (
        <DeleteModal
          name={deleteForm.name}
          deleteUrl={deleteForm.urls.destroy}
          onClose={() => setDeleteForm(false)}
        />
      )}
    </DashboardLayout>

  )

}
export default IndexInsurances;