import React, { ReactNode } from 'react';

interface Props {
    children: ReactNode;
    title?: string;
}

const SimpleLayout: React.FC<Props> = ({ children, title }) => {
    return (
        <div className="min-h-screen bg-gray-50">
            <header className="bg-white shadow-sm border-b">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex items-center">
                            <h1 className="text-xl font-semibold text-gray-900">
                                {title || 'Hospital Management'}
                            </h1>
                        </div>
                        <nav className="flex items-center space-x-4">
                            <a href="/dashboard" className="text-gray-600 hover:text-gray-900">
                                Dashboard
                            </a>
                            <a href="/patients" className="text-gray-600 hover:text-gray-900">
                                Patients
                            </a>
                            <a href="/doctors" className="text-gray-600 hover:text-gray-900">
                                Doctors
                            </a>
                            <a href="/sections" className="text-gray-600 hover:text-gray-900">
                                Sections
                            </a>
                        </nav>
                    </div>
                </div>
            </header>

            <main className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div className="px-4 py-6 sm:px-0">
                    {children}
                </div>
            </main>
        </div>
    );
};

export default SimpleLayout;
