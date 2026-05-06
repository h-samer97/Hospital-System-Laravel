import React, { useState } from 'react';
import { Head, router, useForm } from '@inertiajs/react';
import { UserRole, RoleConfig } from './AuthTypes';

const roleConfigs: Record<UserRole, RoleConfig> = {
    user: { label: 'مريض', title: 'الدخول كمريض', apiEndpoint: '/login' },
    admin: { label: 'مدير', title: 'الدخول أدمن', apiEndpoint: '/login/admin' },
    doctor: { label: 'دكتور', title: 'الدخول دكتور', apiEndpoint: '/login' },
    ray_employee: { label: 'موظف أشعة', title: 'الدخول موظف أشعة', apiEndpoint: '/login' },
    laboratorie_employee: { label: 'موظف مختبر', title: 'الدخول موظف مختبر', apiEndpoint: '/login' }
};

const Login: React.FC = () => {
    const [selectedRole, setSelectedRole] = useState<UserRole | ''>('');
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        role: '',
    });

    const handleLogin = (e: React.FormEvent) => {
        e.preventDefault();
        if (!selectedRole) {
            alert('يرجى اختيار رتبة الدخول');
            return;
        }
        
        const endpoint = selectedRole === 'admin' ? 'login.admin' : 'login';
        
        // Set role in form data before submission
        setData('role', selectedRole);
        
        post(route(endpoint), {
            onFinish: () => reset('password'),
        });
    };

    const handleRoleChange = (role: UserRole) => {
        setSelectedRole(role);
        setData('role', role);
    };

    return (
        <>
            <Head title="Login" />
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
                                            
                                            {/* Error Messages */}
                                            {errors.email && (
                                                <div className="alert alert-danger">
                                                    {errors.email}
                                                </div>
                                            )}
                                            {errors.password && (
                                                <div className="alert alert-danger">
                                                    {errors.password}
                                                </div>
                                            )}
                                            
                                            {/* قائمة اختيار الرتبة */}
                                            <div className="form-group mt-4">
                                                <label>حدد طريقة الدخول</label>
                                                <select 
                                                    className="form-control" 
                                                    value={selectedRole}
                                                    onChange={(e) => handleRoleChange(e.target.value as UserRole)}
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
                                                                value={data.email}
                                                                onChange={(e) => setData('email', e.target.value)}
                                                                required 
                                                            />
                                                        </div>
                                                        <div className="form-group">
                                                            <label>كلمة المرور</label>
                                                            <input 
                                                                className="form-control" 
                                                                type="password" 
                                                                placeholder="Enter your password"
                                                                value={data.password}
                                                                onChange={(e) => setData('password', e.target.value)}
                                                                required 
                                                            />
                                                        </div>
                                                        <button type="submit" className="btn btn-main-primary btn-block" disabled={processing}>
                                                            {processing ? 'جاري تسجيل الدخول...' : 'تسجيل الدخول'}
                                                        </button>
                                                    </form>

                                                    <div className="main-signin-footer mt-5">
                                                        <p><a href={route('password.request')}>نسيت كلمة المرور؟</a></p>
                                                        <p>ليس لديك حساب؟ <a href={route('register')}>إنشاء حساب جديد</a></p>
                                                        
                                                        {/* روابط تسجيل الدخول السريع */}
                                                        <div className="mt-4">
                                                            <p className="text-muted mb-2">تسجيل الدخول المباشر:</p>
                                                            <div className="d-flex flex-wrap gap-2">
                                                                {Object.entries(roleConfigs).map(([key, config]) => (
                                                                    <button
                                                                        key={key}
                                                                        type="button"
                                                                        className="btn btn-outline-primary btn-sm"
                                                                        onClick={() => handleRoleChange(key as UserRole)}
                                                                    >
                                                                        {config.label}
                                                                    </button>
                                                                ))}
                                                            </div>
                                                        </div>
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
        </>
    );
};

export default Login;