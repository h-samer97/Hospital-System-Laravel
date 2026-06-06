import DashboardLayout from '@/Layouts/DashboardLayout';
import styles from './Dashboard.module.css';

interface Admin {
    name: string;
    email: string;
}

interface Props {
    admin: Admin;
}

const stats = [
    { label: 'Total Doctors', value: '48', icon: '👨‍⚕️', color: '#4299e1' },
    { label: 'Patients Today', value: '126', icon: '🧑‍🤝‍🧑', color: '#48bb78' },
    { label: 'Upcoming Appointments', value: '34', icon: '📅', color: '#ed8936' },
];

export default function DashboardIndex({ admin }: Props) {
    return (
        <DashboardLayout title="Dashboard">
            <div className={styles.dashboardPage}>
                <div className={styles.welcomeBanner}>
                    <div>
                        <h2>Hello, {admin.name} 👋</h2>
                        <p>Here's your system summary for today</p>
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