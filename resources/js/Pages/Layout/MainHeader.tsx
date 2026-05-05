import React, { useState } from 'react';
import { Link } from '@inertiajs/react';

// تعريف أنواع البيانات للإشعارات والرسائل لضمان سلامة الكود
interface Message {
    id: number;
    name: string;
    text: string;
    time: string;
    img: string;
}

interface Notification {
    id: number;
    title: string;
    time: string;
    iconClass: string;
    bgClass: string;
}

const MainHeader: React.FC = () => {
    const [isProfileOpen, setIsProfileOpen] = useState(false);
    const [isMsgOpen, setIsMsgOpen] = useState(false);

    return (
        <div className="main-header nav nav-item hor-header">
            <div className="container">
                <div className="main-header-left">
                    <a className="animated-arrow hor-toggle horizontal-navtoggle"><span></span></a>
                    <Link className="header-brand" href="/index">
                        <img src="/assets/img/brand/logo.png" className="desktop-logo" alt="logo" />
                        <img src="/assets/img/brand/favicon.png" className="desktop-logo-1" alt="favicon" />
                    </Link>
                    <div className="main-header-center ml-4">
                        <input className="form-control" placeholder="Search for anything..." type="search" />
                        <button className="btn search-btn"><i className="fe fe-search"></i></button>
                    </div>
                </div>

                <div className="main-header-right">
                    {/* اختيار اللغة - يمكنك استبداله بمكتبة i18next لاحقاً */}
                    <ul className="language-selector">
                        <li><a href="?lang=ar">العربية</a></li>
                        <li><a href="?lang=en">English</a></li>
                    </ul>

                    <div className="nav nav-item navbar-nav-right ml-auto">
                        {/* أيقونة الرسائل */}
                        <div className={`dropdown nav-item ${isMsgOpen ? 'show' : ''}`}>
                            <a className="new nav-link" onClick={() => setIsMsgOpen(!isMsgOpen)}>
                                <svg xmlns="http://www.w3.org/2000/svg" className="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                <span className="pulse-danger"></span>
                            </a>
                            {isMsgOpen && (
                                <div className="dropdown-menu show">
                                    <div className="menu-header-content bg-primary text-left">
                                        <div className="d-flex">
                                            <h6 className="dropdown-title text-white">Messages</h6>
                                            <span className="badge badge-pill badge-warning ml-auto">Mark All Read</span>
                                        </div>
                                    </div>
                                    <div className="main-message-list">
                                        {/* مثال لرسالة واحدة - يمكنك عمل Map للمصفوفة هنا */}
                                        <a href="#" className="p-3 d-flex border-bottom">
                                            <div className="drop-img"><img src="/assets/img/faces/3.jpg" alt="user" /></div>
                                            <div className="wd-90p">
                                                <h5 className="name">Petey Cruiser</h5>
                                                <p className="desc">I'm sorry but I'm not sure...</p>
                                                <p className="time">Mar 15 3:55 PM</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* الملف الشخصي */}
                        <div className="dropdown main-profile-menu">
                            <a className="profile-user d-flex" onClick={() => setIsProfileOpen(!isProfileOpen)}>
                                <img alt="profile" src="/assets/img/faces/6.jpg" />
                            </a>
                            {isProfileOpen && (
                                <div className="dropdown-menu show">
                                    <div className="main-header-profile bg-primary p-3">
                                        <h6>Petey Cruiser</h6>
                                        <span>Premium Member</span>
                                    </div>
                                    <Link className="dropdown-item" href="/profile"><i className="bx bx-user-circle"></i> Profile</Link>
                                    <Link className="dropdown-item" href="/logout" method="post" as="button"><i className="bx bx-log-out"></i> Sign Out</Link>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default MainHeader;