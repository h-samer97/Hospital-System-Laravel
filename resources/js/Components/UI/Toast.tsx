import React, { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';
import { flashMessage } from '@/Pages/Sections/types';
import styles from './Toast.module.css';

interface ToastPageProps {
  auth: {
    user: { id: number; name: string; email: string };
    admin?: { id: number; name: string; email: string };
  };
  ziggy: any;
  flash?: flashMessage;
  errors: Record<string, string>;
  [key: string]: unknown;
}

export default function Toast() {
  const { flash } = usePage<ToastPageProps>().props;
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    if (flash?.message) {
      setVisible(true);
      const timer = setTimeout(() => setVisible(false), 3500);
      return () => clearTimeout(timer);
    }
  }, [flash]);

  if (!visible || !flash) return null;

  return (
    <div className={`${styles.toast} ${styles[flash.type]}`}>
      <span>{flash.message}</span>
      <button onClick={() => setVisible(false)} aria-label="إغلاق">✕</button>
    </div>
  );
}