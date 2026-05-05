import React, { ReactNode, useEffect, useState } from 'react';

interface AuthLayoutProps {
    children: ReactNode;
}

const AuthLayout: React.FC<AuthLayoutProps> = ({ children }) => {
    const [isLoading, setIsLoading] = useState(true);

    // محاكاة للـ Loader عند تحميل الصفحة
    useEffect(() => {
        const timer = setTimeout(() => {
            setIsLoading(false);
        }, 800); // يختفي بعد 800 ملي ثانية
        return () => clearTimeout(timer);
    }, []);

    return (
        <div className="auth-layout-container main-body dark-theme">
            {/* Loader */}
            {isLoading && (
                <div id="global-loader" className="loader-overlay">
                    <img src="/assets/img/loader.svg" className="loader-img" alt="Loader" />
                </div>
            )}

            {/* Main Content */}
            <div className={`auth-content ${!isLoading ? 'fade-in' : 'hidden'}`}>
                {children}
            </div>
        </div>
    );
};

export default AuthLayout;