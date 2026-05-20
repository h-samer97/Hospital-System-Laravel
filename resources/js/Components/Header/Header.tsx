import { usePage } from '@inertiajs/react';
import styles from './Header.module.css';

interface HeaderProps {
    title: string;
    userType: 'Admin' | 'User';
    links?: { label: string; href: string }[];
    onToggleSidebar?: () => void;
}

export default function Header({ title, userType, links, onToggleSidebar }: HeaderProps) {
    const { url } = usePage();

    const userLabel = userType === 'Admin' ? 'Admin' : 'User';
    const userInitial = userType === 'Admin' ? 'A' : 'U';

    return (
        <header className={styles.header}>

            <div className={styles.headerRight}>
                {onToggleSidebar && (
                    <button className={styles.toggleBtn} onClick={onToggleSidebar} aria-label="Toggle menu">
                        ☰
                    </button>
                )}
                <h1 className={styles.headerTitle}>{title}</h1>
            </div>

            <div className={styles.headerLeft}>
                {links && links.length > 0 && (
                    <ul className={styles.headerLinks}>
                        {links.map(link => (
                            <li key={link.href}>
                                <a
                                    href={link.href}
                                    className={`${styles.headerLink} ${url === link.href ? styles.headerLinkActive : ''}`}
                                >
                                    {link.label}
                                </a>
                            </li>
                        ))}
                    </ul>
                )}

                <div className={styles.divider} />

                <div className={styles.userBadge}>
                    <div className={styles.userAvatar}>{userInitial}</div>
                    <span className={styles.userType}>{userLabel}</span>
                </div>
            </div>

        </header>
    );
}