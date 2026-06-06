import { Link, usePage } from '@inertiajs/react';
import styles from './Sidebar.module.css';
import { Home, Users, Calendar, Building2, Beaker, Pill, Settings, LogOut, Stethoscope } from 'lucide-react';

const staticNavItems = [
    { label: 'Dashboard', icon: Home, href: '/dashboard/admin' },
    { label: 'Doctors', icon: Stethoscope, href: '/doctors' },
    { label: 'Patients', icon: Users, href: '/patients' },
    { label: 'Services', icon: Beaker, href: '/services' },
    { label: 'Groups', icon: Building2, href: '/groups' },
    { label: 'Insurances', icon: Pill, href: '/insurances' },
    { label: 'Ambulances', icon: Building2, href: '/ambulances' },
];

export default function Sidebar({ isOpen }: { isOpen: boolean }) {
    const { url, props } = usePage();
    const sections = (props as any).sections || [];

    return (
        <aside className={`${styles.sidebar} ${!isOpen ? styles.sidebarClosed : ''}`}>

            <div className={styles.sidebarBrand}>
                <Building2 className={styles.brandIcon} size={26} />
                <span className={styles.brandText}>Hospital</span>
            </div>

            <nav className={styles.sidebarNav}>
                {staticNavItems.map(item => {
                    const Icon = item.icon;
                    return (
                        <Link
                            key={item.href}
                            href={item.href}
                            className={`${styles.navItem} ${url.startsWith(item.href) ? styles.navItemActive : ''}`}
                        >
                            <Icon className={styles.navIcon} size={18} />
                            <span className={styles.navLabel}>{item.label}</span>
                        </Link>
                    );
                })}

                {sections.length > 0 && (
                    <>
                        <div className={styles.navDivider}>Sections</div>
                        {sections.map((section: any) => (
                            <Link
                                key={section.id}
                                href={`/sections/${section.id}`}
                                className={`${styles.navItem} ${url.startsWith(`/sections/${section.id}`) ? styles.navItemActive : ''}`}
                            >
                                <Building2 className={styles.navIcon} size={18} />
                                <span className={styles.navLabel}>{section.name}</span>
                            </Link>
                        ))}
                    </>
                )}
            </nav>

            <div className={styles.sidebarFooter}>
                <button className={styles.logoutBtn}>
                    <LogOut className={styles.navIcon} size={18} />
                    <span className={styles.logoutLabel}>Logout</span>
                </button>
            </div>

        </aside>
    );
}