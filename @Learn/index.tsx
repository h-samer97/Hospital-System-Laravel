import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Toast from '@/Components/UI/Toast';
import ServiceFormModal from './ServiceFormModal';
import DeleteModal from '@/Components/UI/DeleteModal';
import type { Service } from '@/types/models';
import { timeAgo } from '@/utils/date';
import styles from './Index.module.css';

interface Props {
  services: Service[];
  store_url: string;
}

export default function ServicesIndex({ services, store_url }: Props) {
  const [showAdd, setShowAdd]         = useState(false);
  const [editService, setEditService] = useState<Service | null>(null);
  const [deleteService, setDeleteService] = useState<Service | null>(null);
  const [search, setSearch]           = useState('');

  
}