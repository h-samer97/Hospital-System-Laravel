import { ReactNode, useState } from 'react';
import Sidebar from '@/Components/Sidebar/Sidebar';
import Header from '@/Components/Header/Header';
import styles from './Dashboard.module.css';

interface Props {
    children: ReactNode;
    title?: string;
}

export default function Dashboard({ children, title = 'لوحة التحكم' }: Props) {
    const [sidebarOpen, setSidebarOpen] = useState(true);

    return (
        <div className={styles.dashboardRoot} dir="rtl">
            <Sidebar isOpen={sidebarOpen} />

            <div className={`${styles.dashboardMain} ${sidebarOpen ? styles.dashboardMainSidebarOpen : ''}`}>
                <Header
                    title={title}
                    userType="Admin"
                    onToggleSidebar={() => setSidebarOpen(prev => !prev)}
                    links={[
                        { label: 'الرئيسية',      href: '/' },
                        { label: 'الأقسام',       href: '/sections' },
                        { label: 'الأطباء',       href: '/doctors' },
                        { label: 'الخدمات',       href: '/services' },
                    ]}
                />
                <main className={styles.dashboardContent}>
                    {children}
                </main>
            </div>
        </div>
    );
}