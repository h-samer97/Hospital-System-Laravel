import { Link, usePage } from '@inertiajs/react';

const navItems = [
    { label: 'الرئيسية',      icon: '🏠', route: 'admin.dashboard' },
    { label: 'الأطباء',       icon: '👨‍⚕️', route: 'admin.doctors.index' },
    { label: 'المرضى',        icon: '🧑‍🤝‍🧑', route: 'admin.patients.index' },
    { label: 'المواعيد',      icon: '📅', route: 'admin.appointments.index' },
    { label: 'الأقسام',       icon: '🏥', route: 'admin.departments.index' },
    { label: 'المختبر',       icon: '🔬', route: 'admin.lab.index' },
    { label: 'الصيدلية',      icon: '💊', route: 'admin.pharmacy.index' },
    { label: 'الإعدادات',     icon: '⚙️', route: 'admin.settings.index' },
];

export default function Sidebar({ isOpen }: { isOpen: boolean }) {
    const { url } = usePage();

    return (
        <aside className={`sidebar ${isOpen ? 'open' : 'closed'}`}>
            <div className="sidebar-brand">
                <span className="brand-icon">🏥</span>
                {isOpen && <span className="brand-text">Hospital</span>}
            </div>

            <nav className="sidebar-nav">
                {navItems.map(item => (
                    <Link
                        key={item.route}
                        href={route(item.route)}
                        className={`nav-item ${url.startsWith('/' + item.route.replace('admin.', 'admin/').replace('.index', '')) ? 'active' : ''}`}
                    >
                        <span className="nav-icon">{item.icon}</span>
                        {isOpen && <span className="nav-label">{item.label}</span>}
                    </Link>
                ))}
            </nav>

            <style>{`
                .sidebar {
                    position: fixed;
                    top: 0; right: 0;
                    height: 100vh;
                    background: #1a202c;
                    width: 260px;
                    transition: width 0.3s;
                    z-index: 100;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                }

                .sidebar.closed { width: 64px; }

                .sidebar-brand {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 20px 16px;
                    border-bottom: 1px solid #2d3748;
                }

                .brand-icon { font-size: 28px; }

                .brand-text {
                    color: #fff;
                    font-size: 20px;
                    font-weight: 700;
                }

                .sidebar-nav {
                    display: flex;
                    flex-direction: column;
                    gap: 4px;
                    padding: 16px 8px;
                }

                .nav-item {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 12px 12px;
                    border-radius: 8px;
                    color: #a0aec0;
                    text-decoration: none;
                    font-size: 14px;
                    font-weight: 500;
                    transition: background 0.2s, color 0.2s;
                }

                .nav-item:hover { background: #2d3748; color: #fff; }

                .nav-item.active { background: #4299e1; color: #fff; }

                .nav-icon { font-size: 18px; min-width: 24px; text-align: center; }
            `}</style>
        </aside>
    );
}