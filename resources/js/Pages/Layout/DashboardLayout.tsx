import React, { ReactNode } from 'react';
import Navigation from './Navigation'; 

interface Props {
    children: ReactNode;
    header?: ReactNode;
}

const DashboardLayout: React.FC<Props> = ({ children, header }) => {
    return (
        <div className="layout-container">
            <Navigation />

            {/* Page Heading */}
            {header && (
                <header className="layout-header">
                    <div className="header-wrapper">
                        {header}
                    </div>
                </header>
            )}

            {/* Page Content */}
            <main className="layout-main">
                {children}
            </main>
        </div>
    );
};

export default DashboardLayout;