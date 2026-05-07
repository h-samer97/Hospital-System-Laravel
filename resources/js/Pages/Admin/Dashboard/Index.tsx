import DashboardLayout from '@/Layouts/DashboardLayout';
import styles from './Index.module.css';

interface Admin {
    name: string;
    email: string;
}

interface Props {
    admin: Admin;
}

const stats = [
    { label: 'إجمالي الأطباء',  value: '48',  icon: '👨‍⚕️', color: '#4299e1' },
    { label: 'المرضى اليوم',    value: '126', icon: '🧑‍🤝‍🧑', color: '#48bb78' },
    { label: 'المواعيد القادمة', value: '34',  icon: '📅', color: '#ed8936' },
    { label: 'الأقسام',         value: '12',  icon: '🏥', color: '#9f7aea' },
];

export default function DashboardIndex({ admin }: Props) {
    return (
        <DashboardLayout title="الرئيسية">
            <div className={styles.dashboardPage}>
                <div className={styles.welcomeBanner}>
                    <div>
                        <h2>مرحباً، {admin.name} 👋</h2>
                        <p>إليك ملخص النظام لهذا اليوم</p>
                    </div>
                </div>

                <div className={styles.statsGrid}>
                    {stats.map(stat => (
                        <div key={stat.label} className={styles.statCard}>
                            <div 
                                className={styles.statIcon} 
                                style={{ background: stat.color + '20', color: stat.color }}
                            >
                                {stat.icon}
                            </div>
                            <div className={styles.statInfo}>
                                <span className={styles.statValue}>{stat.value}</span>
                                <span className={styles.statLabel}>{stat.label}</span>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </DashboardLayout>
    );
}