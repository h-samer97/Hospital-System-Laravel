import React, { useState } from 'react';
import { Link } from '@inertiajs/react';

const Navbar: React.FC = () => {
    // حالة للتحكم في القائمة المستجيبة (Mobile Menu) والدروب داون
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [isProfileOpen, setIsProfileOpen] = useState(false);

    // محاكاة لبيانات المستخدم من Laravel Auth
    const user = { name: "Samer", email: "samer@example.com" };

    return (
        <nav className="navbar-container">
            {/* Primary Navigation Menu */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between h-16">
                    <div className="flex">
                        {/* Logo */}
                        <div className="shrink-0 flex items-center">
                            <Link href="/dashboard">
                                <img src="/logo.svg" className="block h-9 w-auto" alt="Application Logo" />
                            </Link>
                        </div>

                        {/* Navigation Links (Desktop) */}
                        <div className="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <Link href="/dashboard" className="nav-link active">
                                Dashboard
                            </Link>
                        </div>
                    </div>

                    {/* Settings Dropdown (Desktop) */}
                    <div className="hidden sm:flex sm:items-center sm:ms-6 relative">
                        <button 
                            onClick={() => setIsProfileOpen(!isProfileOpen)}
                            className="profile-dropdown-btn"
                        >
                            <div>{user.name}</div>
                            <div className="ms-1">
                                <svg className="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        {isProfileOpen && (
                            <div className="dropdown-content">
                                <Link href="/profile" className="dropdown-link">Profile</Link>
                                <button className="dropdown-link text-left w-full">Log Out</button>
                            </div>
                        )}
                    </div>

                    {/* Hamburger (Mobile) */}
                    <div className="-me-2 flex items-center sm:hidden">
                        <button 
                            onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                            className="hamburger-btn"
                        >
                            <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                {!isMobileMenuOpen ? (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                                ) : (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                )}
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {/* Responsive Navigation Menu (Mobile) */}
            <div className={`${isMobileMenuOpen ? 'block' : 'hidden'} sm:hidden`}>
                <div className="pt-2 pb-3 space-y-1">
                    <Link href="/dashboard" className="responsive-nav-link active">Dashboard</Link>
                </div>

                {/* Responsive Settings */}
                <div className="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    <div className="px-4">
                        <div className="font-medium text-base text-gray-800 dark:text-gray-200">{user.name}</div>
                        <div className="font-medium text-sm text-gray-500">{user.email}</div>
                    </div>
                    <div className="mt-3 space-y-1">
                        <Link href="/profile" className="responsive-nav-link">Profile</Link>
                        <button className="responsive-nav-link text-left w-full">Log Out</button>
                    </div>
                </div>
            </div>
        </nav>
    );
};

export default Navbar;