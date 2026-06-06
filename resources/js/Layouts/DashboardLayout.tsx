import React, { useState } from 'react';
import { usePage } from '@inertiajs/react';
import Sidebar from '@/Components/Sidebar/Sidebar';
import Header from '@/Components/Header/Header';

interface Props {
    title: string;
    children: React.ReactNode;
}

export default function DashboardLayout({ title, children }: Props) {
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const { url } = usePage();

    return (
        <div className="min-h-screen bg-gray-50 flex">
            <Sidebar isOpen={sidebarOpen} />

            <div className={`flex-1 transition-all duration-300 ${sidebarOpen ? 'ml-[200px]' : 'ml-[64px]'}`}>
                <Header
                    title={title}
                    userType="Admin"
                    onToggleSidebar={() => setSidebarOpen(prev => !prev)}
                    links={[
                        { label: 'الرئيسية', href: '/dashboard/admin' },
                        { label: 'الأقسام', href: '/sections' },
                        { label: 'الأطباء', href: '/doctors' },
                        { label: 'الخدمات', href: '/services' },
                    ]}
                />

                <main className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {children}
                </main>
            </div>
        </div>
    );
}
