import React, { useState, FormEvent } from "react";
import axios from "axios";
import { RoleKey, RoleConfig, LoginFormData } from "./types";

interface Props {
  role: RoleKey;
  config: RoleConfig;
}

const MultiLoginForm: React.FC<Props> = ({ role, config }) => {
  // حالة الحقول
  const [email, setEmail]       = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading]   = useState(false);
  const [error, setError]       = useState<string | null>(null);

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault(); // منع إعادة تحميل الصفحة
    setLoading(true);
    setError(null);

    const data: LoginFormData = { email, password, role };

    try {
      // إرسال البيانات إلى الـ API
      const response = await axios.post(config.action, data);
      // بعد النجاح يمكن توجيه المستخدم
      window.location.href = response.data.redirect ?? "/dashboard";
    } catch (err: unknown) {
      // معالجة الأخطاء بشكل آمن مع TypeScript
      if (axios.isAxiosError(err)) {
        setError(err.response?.data?.message ?? "حدث خطأ، حاول مجدداً.");
      } else {
        setError("حدث خطأ غير متوقع.");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div id={role} className="mt-6">
      <h2 className="text-xl font-semibold mb-4">{config.label}</h2>

      {/* عرض رسالة الخطأ إن وجدت */}
      {error && (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          <ul className="list-disc list-inside"><li>{error}</li></ul>
        </div>
      )}

      {/*
        onSubmit على الـ form بدلاً من onClick على الزر
        هذا يتيح إرسال النموذج بضغط Enter أيضاً
      */}
      <form onSubmit={handleSubmit} action={config.action} method="post">

        <div className="mb-4">
          <label htmlFor={`email-${role}`} className="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
          <input
            id={`email-${role}`}
            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            type="email"
            name="email"
            placeholder="أدخل بريدك الإلكتروني"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            autoFocus
            autoComplete="email"
          />
        </div>

        <div className="mb-4">
          <label htmlFor={`pass-${role}`} className="block text-sm font-medium text-gray-700 mb-2">كلمة المرور</label>
          <input
            id={`pass-${role}`}
            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            type="password"
            name="password"
            placeholder="أدخل كلمة المرور"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
            autoComplete="current-password"
          />
        </div>

        <button
          type="submit"
          className="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
          disabled={loading}
        >
          {loading ? "جاري التحقق..." : "تسجيل الدخول"}
        </button>
      </form>

      <div className="mt-5 text-sm">
        <p className="mb-2"><a href="/forgot-password" className="text-blue-600 hover:underline">نسيت كلمة المرور؟</a></p>
        <p>
          ليس لديك حساب؟{" "}
          <a href="/signup" className="text-blue-600 hover:underline">إنشاء حساب جديد</a>
        </p>
      </div>
    </div>
  );
};

export default MultiLoginForm;