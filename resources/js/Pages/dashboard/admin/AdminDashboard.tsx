import React from 'react';
import DashboardStatCard from './DashboardStatCard';
import RecentCustomers from './RecentCustomers';

const AdminDashboard: React.FC = () => {
    return (
        <div className="main-content-body">
            {/* Page Header */}
            <div className="breadcrumb-header justify-content-between">
                <div className="left-content">
                    <h2 className="main-content-title tx-24 mg-b-1">أهلاً بك يا Samer</h2>
                    <p className="mg-b-0 text-muted">لوحة مراقبة أداء النظام والمبيعات.</p>
                </div>
                <div className="main-dashboard-header-right d-flex">
                    <div className="me-3">
                        <label className="tx-13">مبيعات الأوفلاين</label>
                        <h5>783,675</h5>
                    </div>
                    <div>
                        <label className="tx-13">مبيعات الأونلاين</label>
                        <h5>563,275</h5>
                    </div>
                </div>
            </div>

            {/* Stats Row */}
            <div className="row row-sm">
                <DashboardStatCard 
                    title="طلبات اليوم" 
                    value="$5,74.12" 
                    percentage="+427" 
                    isIncrease={true} 
                    gradientClass="bg-primary-gradient" 
                    chartData="5,9,5,6,4,12,18,14,10,15"
                />
                <DashboardStatCard 
                    title="أرباح اليوم" 
                    value="$1,230.17" 
                    percentage="-23.09%" 
                    isIncrease={false} 
                    gradientClass="bg-danger-gradient" 
                    chartData="3,2,4,6,12,14,8,7"
                />
                {/* كرر المكون لبقية الكروت */}
            </div>

            <div className="row row-sm mt-4">
                <div className="col-xl-4 col-md-12">
                    <RecentCustomers />
                </div>
                <div className="col-xl-8 col-md-12">
                    {/* هنا يمكن إضافة جدول الأرباح الأخيرة Recent Earnings */}
                </div>
            </div>
        </div>
    );
};