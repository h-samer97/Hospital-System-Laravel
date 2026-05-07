import { ReactNode, useState } from 'react';
import Sidebar from './Sidebar';
import Header from './Header';
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
                    onToggleSidebar={() => setSidebarOpen(!sidebarOpen)}
                />
                <main className={styles.dashboardContent}>
                    {children}
                </main>
            </div>
        </div>
    );
}