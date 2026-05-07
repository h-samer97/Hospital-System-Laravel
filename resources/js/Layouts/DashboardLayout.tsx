import React from 'react';

interface Props {
    title: string;
    children: React.ReactNode;
}

export default function DashboardLayout({ title, children }: Props) {
    return (
        <div className="min-h-screen bg-gray-50">
            <header className="bg-white shadow-sm border-b">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16">
                        <h1 className="text-xl font-semibold text-gray-900">{title}</h1>
                        <nav className="flex space-x-4">
                            <a href="/admin/dashboard" className="text-gray-600 hover:text-gray-900">الرئيسية</a>
                            <a href="/admin/doctors" className="text-gray-600 hover:text-gray-900">الأطباء</a>
                            <a href="/admin/patients" className="text-gray-600 hover:text-gray-900">المرضى</a>
                            <a href="/admin/appointments" className="text-gray-600 hover:text-gray-900">المواعيد</a>
                        </nav>
                    </div>
                </div>
            </header>
            
            <main className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {children}
            </main>
        </div>
    );
}
