import React from 'react';
import { useLocale } from '@/hooks/useLocale';
import styles from './LanguageSwitcher.module.css';

export default function LanguageSwitcher() {
  const { locale, getLocaleUrl } = useLocale();
  const isAr = locale === 'ar';

  return (
    <div className={styles.switcher}>
      <a
        href={getLocaleUrl('ar')}
        className={`${styles.btn} ${isAr ? styles.active : ''}`}
      >
        العربية
      </a>
      <a
        href={getLocaleUrl('en')}
        className={`${styles.btn} ${!isAr ? styles.active : ''}`}
      >
        EN
      </a>
    </div>
  );
}