import React, { useState, useRef, useEffect } from 'react';
import { useLocale } from '@/hooks/useLocale';
import { SupportedLocale, LocaleCode } from '@/types/locale';
import styles from './LanguageSwitcher.module.css';

const FLAGS: Record<LocaleCode, string> = {
    ar: '🇸🇦',
    en: '🇺🇸',
};

export default function LanguageSwitcher() {
    const { locale, supportedLocales } = useLocale();
    const [isOpen, setIsOpen]           = useState(false);
    const wrapperRef                    = useRef<HTMLDivElement>(null);

    useEffect(() => {
        const handler = (e: MouseEvent) => {
            if (wrapperRef.current && !wrapperRef.current.contains(e.target as Node)) {
                setIsOpen(false);
            }
        };
        document.addEventListener('mousedown', handler);
        return () => document.removeEventListener('mousedown', handler);
    }, []);

    const current = supportedLocales.find(l => l.code === locale);

    return (
        <div className={styles.wrapper} ref={wrapperRef}>

            {/* زر اللغة الحالية */}
            <button
                className={styles.trigger}
                onClick={() => setIsOpen(p => !p)}
                aria-haspopup="listbox"
                aria-expanded={isOpen}
            >
                <span>{FLAGS[locale]}</span>
                <span>{current?.native}</span>
                <span className={`${styles.arrow} ${isOpen ? styles.arrowOpen : ''}`}>▾</span>
            </button>

            {/* القائمة — URL جاهز من mcamara */}
            {isOpen && (
                <ul className={styles.dropdown} role="listbox">
                    {supportedLocales.map((loc: SupportedLocale) => (
                        <li key={loc.code} role="option" aria-selected={loc.code === locale}>
                            <a
                                href={loc.url}
                                className={`${styles.option} ${loc.code === locale ? styles.active : ''}`}
                            >
                                <span>{FLAGS[loc.code as LocaleCode]}</span>
                                <span>{loc.native}</span>
                            </a>
                        </li>
                    ))}
                </ul>
            )}
        </div>
    );
}