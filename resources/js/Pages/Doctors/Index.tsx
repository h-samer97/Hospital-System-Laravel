import Header from "@/Components/Header/Header";
import Sidebar from "@/Components/Sidebar/Sidebar";
import Toast from "@/Components/UI/Toast";
import AddDoctor from "./AddDoctor";
import { Doctor, Section, Image } from "./types";
import { useState } from "react";

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

    const filteredDoctors = doctors.filter(doctor => doctor.name.includes(search) // SaMeR
                                                  || doctor.name.toLocaleLowerCase().includes(search.toLocaleLowerCase())
                                                  || doctor.name.toLocaleUpperCase().includes(search.toLocaleUpperCase()));

    return (
        <div>
           <input type="search" onChange={(e) => setSearch(e.target.value)} />
           <ul>
                {filteredDoctors.map(doctor => (
                    <li key={doctor.id}>{doctor.name}</li>
                ))}
           </ul>
        </div>
    );
}