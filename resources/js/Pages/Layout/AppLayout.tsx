import React, { ReactNode, useEffect, useState } from 'react';
import MainHeader from './MainHeader';
import Sidebar from './Sidebar';
import Footer from './Footer';

interface Props {
    children: ReactNode;
    pageHeader?: ReactNode;
}

const AppLayout: React.FC<Props> = ({ children, pageHeader }) => {
    const [loading, setLoading] = useState(true);

    // محاكاة للـ Global Loader
    useEffect(() => {
        const timer = setTimeout(() => setLoading(false), 1000);
        return () => clearTimeout(timer);
    }, []);

    return (
        <div className="main-body dark-theme">
            {/* Loader */}
            {loading && (
                <div id="global-loader">
                    <img src="/assets/img/loader.svg" className="loader-img" alt="Loader" />
                </div>
            )}

            <div className={`page-wrapper ${loading ? 'd-none' : ''}`}>
                <MainHeader />
                <Sidebar />

                <div className="main-content horizontal-content">
                    <div className="container">
                        {/* Page Header */}
                        {pageHeader && <div className="page-header">{pageHeader}</div>}
                        
                        {/* Main Content (يحل محل @yield) */}
                        <main className="content-area">
                            {children}
                        </main>

                        <Footer />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AppLayout;