import React, { useState }  from "react";
import styles from "./GroupFormModal.module.css";
import { Service, GroupItem, GroupFormData } from "./types";
import { useForm } from "@inertiajs/react";
import { Group } from "lucide-react";


interface Props {
    // onClose: () => void;
    onSuccess: () => void;
    
    services: Service[];
    urlStore: string;
}

const emptyItem = (): GroupItem => ({
  service_id:   '',
  quantity:     1,
  is_saved:     false,
  service_name: '',
  unit_price:   0,
});


const GroupFormModal: React.FC<Props> = ({ urlStore, services }) => {

    const [items, setItems]             = useState<GroupItem[]>([emptyItem()]);
    const [discount, setDiscount]       = useState<number>(0);
    const [tax, setTax]                 = useState<number>(17);
    const [name, setName]               = useState<string>('');
    const [notes, setNotes]             = useState<string>('');
    const [logError, setLogError]       = useState<string | null>('');
    const {post, errors, processing}    = useForm<GroupFormData>();

    // Calc SubTotal

    // const subTotal = items.filter(i => i.is_saved).reduce

    return <h1 className={styles.formTitle}>Group Form Modal , {services[0]?.name}</h1>;

};



export default GroupFormModal;