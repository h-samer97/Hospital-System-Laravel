import { Link, usePage } from '@inertiajs/react';
import styles from './Sidebar.module.css';

const navItems = [
    { label: 'الرئيسية',  icon: '🏠', href: '/dashboard/admin' },
    { label: 'الأطباء',   icon: '👨‍⚕️', href: '/admin/doctors' },
    { label: 'المرضى',    icon: '🧑‍🤝‍🧑', href: '/admin/patients' },
    { label: 'المواعيد',  icon: '📅', href: '/admin/appointments' },
    { label: 'الأقسام',   icon: '🏥', href: '/admin/departments' },
    { label: 'المختبر',   icon: '🔬', href: '/admin/lab' },
    { label: 'الصيدلية',  icon: '💊', href: '/admin/pharmacy' },
    { label: 'الإعدادات', icon: '⚙️', href: '/admin/settings' },
];

export default function Sidebar({ isOpen }: { isOpen: boolean }) {
    const { url } = usePage();

    return (
        <aside className={`${styles.sidebar} ${!isOpen ? styles.sidebarClosed : ''}`}>

            <div className={styles.sidebarBrand}>
                <span className={styles.brandIcon}>🏥</span>
                <span className={styles.brandText}>Hospital</span>
            </div>

            <nav className={styles.sidebarNav}>
                {navItems.map(item => (
                    <Link
                        key={item.href}
                        href={item.href}
                        className={`${styles.navItem} ${url.startsWith(item.href) ? styles.navItemActive : ''}`}
                    >
                        <span className={styles.navIcon}>{item.icon}</span>
                        <span className={styles.navLabel}>{item.label}</span>
                    </Link>
                ))}
            </nav>

            <div className={styles.sidebarFooter}>
                <button className={styles.logoutBtn}>
                    <span className={styles.navIcon}>🚪</span>
                    <span className={styles.logoutLabel}>تسجيل الخروج</span>
                </button>
            </div>

        </aside>
    );
}