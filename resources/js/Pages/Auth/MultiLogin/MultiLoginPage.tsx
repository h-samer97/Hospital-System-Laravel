import { RoleConfig } from "./types";
import { RoleKey } from "./types";
import MultiLoginForm from "./MultiLoginForm";
import React, { useState } from "react";
const ROLES: Record<RoleKey, RoleConfig> = {
  admin: { label: "الدخول كمشرف", action: "/login/admin", icon: "shield" },
};

const MultiLoginPage: React.FC = () => {

  const [selectedRole, setSelectedRole] = useState<RoleKey | null>(null);

  const handleRoleChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    setSelectedRole(e.target.value as RoleKey);
  }

  return (
    <div className="min-h-screen flex">
      {/* ===== الجانب الأيسر: صورة ===== */}
      <div className="hidden md:flex md:w-1/2 bg-blue-100 items-center justify-center">
        <img src="/dashboard/img/media/login.png" alt="login illustration" />
      </div>

      {/* ===== الجانب الأيمن: النموذج ===== */}
      <div className="w-full md:w-1/2 bg-white flex items-center py-2">
        <div className="container mx-auto px-4">

          {/* الشعار */}
          <div className="mb-5 flex">
            <img src="/dashboard/img/brand/favicon.png" className="h-10" alt="logo" />
            <h1 className="text-2xl ml-1">
              Va<span>le</span>x
            </h1>
          </div>

          <h2 className="text-2xl font-bold mb-4">مرحباً بك</h2>

          {/* ===== قائمة اختيار الدور ===== */}
          <div className="mb-4">
            <label htmlFor="sectionChooser" className="block text-sm font-medium text-gray-700 mb-2">اختر نوع الحساب</label>
            <select
              id="sectionChooser"
              className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={selectedRole ?? ""}
              onChange={handleRoleChange}
            >
              <option value="" disabled>اختر من القائمة...</option>
              {/* نولّد الخيارات ديناميكياً من كائن ROLES */}
              {(Object.entries(ROLES) as [RoleKey, RoleConfig][]).map(
                ([key, config]) => (
                  <option key={key} value={key}>
                    {config.label}
                  </option>
                )
              )}
            </select>
          </div>

          {/* ===== عرض النموذج فقط إذا اختار المستخدم دوراً ===== */}
          {selectedRole && (
            <MultiLoginForm
              role={selectedRole}
              config={ROLES[selectedRole]}
            />
          )}

        </div>
      </div>
    </div>
  );
};

export default MultiLoginPage;