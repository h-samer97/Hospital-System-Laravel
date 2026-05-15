import { Doctor, Section, Image } from "./types";
import { useState } from "react";
import styles from "./Index.module.css";
import AddDoctor from "./AddDoctor";
import DoctorFormModal from "./DoctorFormModal";
// import DeleteModal from "../Components/UI/DeleteModal";
import DeleteModal from "@/Components/UI/DeleteModal";

// TODO => FIX DOCTORS

interface Props {
    doctors: Doctor[]; // Doctor[] doctors = [{}, {}, {}]
    sections: Section[];
    images: Image[];
    store_url: string;
}

export default function Index({ doctors, sections, images, store_url }: Props) {
    
    const [showAdd, setShowAdd]             = useState(false);
    const [editDoctor, setEditDoctor]       = useState<Doctor | null>(null);
    const [deleteDoctor, setDeleteDoctor]   = useState<Doctor | null>(null);
    const [search, setSearch]               = useState('');

    const filteredDoctors = doctors.filter(doctor => doctor.name.includes(search)
                                                  || doctor.name.toLocaleLowerCase().includes(search.toLocaleLowerCase())
                                                  || doctor.name.toLocaleUpperCase().includes(search.toLocaleUpperCase()));

    return (
        <>
            <div className={styles.page}>
                <div className={styles.header}>
                    <div>
                        <h1>Doctors</h1>
                        <p className={styles.breadcrumb}>Dashboard / Doctors List</p>
                    </div>
                    <button className={styles.addBtn} onClick={() => setShowAdd(true)}>
                        + Add Doctor
                    </button>
                </div>

                <div className={styles.card}>
                    <div className={styles.toolbar}>
                        <input
                            type="text"
                            placeholder="Search by name or email..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className={styles.searchInput}
                        />
                        <span className={styles.count}>{filteredDoctors.length} doctors</span>
                    </div>

                    <div className={styles.tableWrapper}>
                        <table className={styles.table}>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Section</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {filteredDoctors.length === 0 ? (
                                    <tr>
                                        <td colSpan={9} className={styles.empty}>No doctors found</td>
                                    </tr>
                                ) : (
                                    filteredDoctors.map((doctor, i) => (
                                        <tr key={doctor.id}>
                                            <td>{i + 1}</td>
                                            <td>
                                                <img
                                                    src={doctor.image_url ?? '/images/default-doctor.png'}
                                                    alt={doctor.name}
                                                    className={styles.avatar}
                                                />
                                            </td>
                                            <td>
                                                <div className={styles.nameCell}>
                                                    <span>{doctor.name}</span>
                                                </div>
                                            </td>
                                            <td>{doctor.section?.name ?? '—'}</td>
                                            <td>{doctor.email}</td>
                                            <td>{doctor.phone}</td>
                                            <td>{doctor.price} EGP</td>
                                            <td>
                                                <span className={doctor.is_active ? styles.badgeActive : styles.badgeInactive}>
                                                    {doctor.is_active ? 'Active' : 'Inactive'}
                                                </span>
                                            </td>
                                            <td className={styles.actions}>
                                                <button
                                                    className={styles.editBtn}
                                                    onClick={() => setEditDoctor(doctor)}
                                                    title="Edit"
                                                >✏️</button>
                                                <button
                                                    className={styles.deleteBtn}
                                                    onClick={() => setDeleteDoctor(doctor)}
                                                    title="Delete"
                                                >🗑️</button>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {showAdd && (
                <AddDoctor sections={sections} images={images} onClose={() => setShowAdd(false)} store_url={store_url}/>
            )}

            {editDoctor && (
                <DoctorFormModal
                  mode="edit"
                  doctor={editDoctor}
                  sections={sections}
                  storeUrl={editDoctor.edit_url}
                  onClose={() => setEditDoctor(null)}
                />
            )}
            {deleteDoctor && (
                <DeleteModal
                  name={deleteDoctor.name}
                  deleteUrl={deleteDoctor?.delete_url}
                  onClose={() => setDeleteDoctor(null)}
                />
            )}
        </>
    );
}
