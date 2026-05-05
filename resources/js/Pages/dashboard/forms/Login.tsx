import React, { useState } from 'react';
import { UserRole, RoleConfig } from './AuthTypes';

const roleConfigs: Record<UserRole, RoleConfig> = {
    user: { label: 'مريض', title: 'الدخول كمريض', apiEndpoint: '/login' },
    admin: { label: 'مدير', title: 'الدخول أدمن', apiEndpoint: '/admin/login' },
    doctor: { label: 'دكتور', title: 'الدخول دكتور', apiEndpoint: '/doctor/login' },
    ray_employee: { label: 'موظف أشعة', title: 'الدخول موظف أشعة', apiEndpoint: '/ray/login' },
    laboratorie_employee: { label: 'موظف مختبر', title: 'الدخول موظف مختبر', apiEndpoint: '/lab/login' }
};

const Login: React.FC = () => {
    const [selectedRole, setSelectedRole] = useState<UserRole | ''>('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');

    const handleLogin = (e: React.FormEvent) => {
        e.preventDefault();
        if (!selectedRole) return alert('يرجى اختيار رتبة الدخول');
        
        // هنا يتم إرسال البيانات إلى الـ API المناسب
        console.log(`Logging in to ${roleConfigs[selectedRole].apiEndpoint}`, { email, password });
    };

    return (
        <div className="container-fluid">
            <div className="row no-gutter">
                {/* الجزء الأيسر: الصورة (تختفي في الشاشات الصغيرة) */}
                <div className="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
                    <div className="my-auto mx-auto text-center">
                        <img src="/assets/img/media/login.png" className="ht-xl-80p wd-md-100p" alt="login-img" />
                    </div>
                </div>

                {/* الجزء الأيمن: محتوى تسجيل الدخول */}
                <div className="col-md-6 col-lg-6 col-xl-5 bg-white">
                    <div className="login d-flex align-items-center py-2">
                        <div className="container p-0">
                            <div className="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div className="card-sigin">
                                    {/* Logo Section */}
                                    <div className="mb-5 d-flex">
                                        <img src="/assets/img/brand/favicon.png" className="sign-favicon ht-40" alt="logo" />
                                        <h1 className="main-logo1 ml-1 mr-0 my-auto tx-28">Va<span>le</span>x</h1>
                                    </div>

                                    <div className="main-signup-header">
                                        <h2>مرحباً بك</h2>
                                        
                                        {/* قائمة اختيار الرتبة */}
                                        <div className="form-group mt-4">
                                            <label>حدد طريقة الدخول</label>
                                            <select 
                                                className="form-control" 
                                                value={selectedRole}
                                                onChange={(e) => setSelectedRole(e.target.value as UserRole)}
                                            >
                                                <option value="" disabled>اختار من القائمة</option>
                                                {Object.entries(roleConfigs).map(([key, config]) => (
                                                    <option key={key} value={key}>{config.label}</option>
                                                ))}
                                            </select>
                                        </div>

                                        {/* يظهر النموذج فقط عند اختيار رتبة */}
                                        {selectedRole && (
                                            <div className="panel mt-4">
                                                <h4 className="mb-3">{roleConfigs[selectedRole].title}</h4>
                                                <form onSubmit={handleLogin}>
                                                    <div className="form-group">
                                                        <label>البريد الإلكتروني</label>
                                                        <input 
                                                            className="form-control" 
                                                            type="email" 
                                                            placeholder="Enter your email"
                                                            value={email}
                                                            onChange={(e) => setEmail(e.target.value)}
                                                            required 
                                                        />
                                                    </div>
                                                    <div className="form-group">
                                                        <label>كلمة المرور</label>
                                                        <input 
                                                            className="form-control" 
                                                            type="password" 
                                                            placeholder="Enter your password"
                                                            value={password}
                                                            onChange={(e) => setPassword(e.target.value)}
                                                            required 
                                                        />
                                                    </div>
                                                    <button type="submit" className="btn btn-main-primary btn-block">تسجيل الدخول</button>
                                                </form>

                                                <div className="main-signin-footer mt-5">
                                                    <p><a href="#!">نسيت كلمة المرور؟</a></p>
                                                    <p>ليس لديك حساب؟ <a href="#!">إنشاء حساب جديد</a></p>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Login;