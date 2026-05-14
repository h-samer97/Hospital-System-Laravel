import { Doctor, Section, Image } from "./types";

interface Props {
    sections: Section[];
    images: Image[];
    store_url: string;
    onClose: () => void;
    editDoctor?: Doctor | null;
}

export default function AddDoctor({ sections, images, store_url, onClose, editDoctor }: Props) {
    return (
        <div>
            <h2>{editDoctor ? 'Edit Doctor' : 'Add Doctor'}</h2>
            {/* Add your form here */}
        </div>
    );
}
