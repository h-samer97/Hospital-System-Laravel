import React, { useState } from 'react';
import { Link } from '@inertiajs/react';

// تعريف واجهة البيانات للعناصر التي تحتوي على قوائم فرعية
interface MenuItem {
    label: string;
    icon?: React.ReactNode;
    path?: string;
    category?: string;
    children?: { label: string; path: string }[];
}

const Sidebar: React.FC = () => {
    // حالة للتحكم في القوائم المنسدلة
    const [openMenus, setOpenMenus] = useState<string | null>(null);

    const toggleMenu = (label: string) => {
        setOpenMenus(openMenus === label ? null : label);
    };

    return (
        <aside className="app-sidebar sidebar-scroll">
            <div className="main-sidebar-header">
                <Link href="/admin/dashboard">
                    <img src="/Dashboard/img/brand/logo.png" className="main-logo" alt="logo" />
                </Link>
            </div>

            <div className="main-sidemenu">
                {/* معلومات المستخدم */}
                <div className="app-sidebar__user">
                    <div className="user-info text-center">
                        <img src="/Dashboard/img/faces/6.jpg" className="avatar avatar-xl brround" alt="user" />
                        <h4 className="font-weight-semibold mt-3">Samer A.</h4>
                        <span className="text-muted">Full-Stack Developer</span>
                    </div>
                </div>

                <ul className="side-menu">
                    <li className="side-item-category">Main</li>
                    
                    <li className="slide">
                        <Link className="side-menu__item" href="/admin">
                            <svg className="side-menu__icon" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg>
                            <span className="side-menu__label">Dashboard</span>
                        </Link>
                    </li>

                    <li className="side-item-category">General</li>

                    {/* قائمة الأقسام المنسدلة */}
                    <li className={`slide ${openMenus === 'sections' ? 'is-expanded' : ''}`}>
                        <a className="side-menu__item" onClick={() => toggleMenu('sections')}>
                            <svg className="side-menu__icon" viewBox="0 0 24 24"><path d="M19 5H5v14h14V5zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
                            <span className="side-menu__label">Sections</span>
                            <i className={`angle fe fe-chevron-${openMenus === 'sections' ? 'down' : 'left'}`}></i>
                        </a>
                        <ul className={`slide-menu ${openMenus === 'sections' ? 'open' : ''}`}>
                            <li><Link className="slide-item" href="/sections">View All</Link></li>
                        </ul>
                    </li>

                    {/* قائمة الأطباء */}
                    <li className={`slide ${openMenus === 'doctors' ? 'is-expanded' : ''}`}>
                        <a className="side-menu__item" onClick={() => toggleMenu('doctors')}>
                            <svg className="side-menu__icon" viewBox="0 0 24 24"><path d="M12 13c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            <span className="side-menu__label">Doctors</span>
                            <i className={`angle fe fe-chevron-${openMenus === 'doctors' ? 'down' : 'left'}`}></i>
                        </a>
                        <ul className={`slide-menu ${openMenus === 'doctors' ? 'open' : ''}`}>
                            <li><Link className="slide-item" href="/doctors">All Doctors</Link></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
    );
};

export default Sidebar;