import { Doctor, Section, Image, Appointment } from "./types";

interface Props {
    sections: Section[];
    images: Image[];
    appointments: Appointment[];
    store_url: string;
    onClose: () => void;
    editDoctor?: Doctor | null;
}

export default function AddDoctor({ sections, images, appointments, store_url, onClose, editDoctor }: Props) {
    return (
        <div>
            <h2>{editDoctor ? 'Edit Doctor' : 'Add Doctor'}</h2>
            {/* Add your form here */}
        </div>
    );
}
