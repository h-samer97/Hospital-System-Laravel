import React from 'react';
import { StatCardProps } from './types';

const DashboardStatCard: React.FC<StatCardProps> = ({ title, value, percentage, isIncrease, gradientClass, chartData }) => {
    return (
        <div className="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div className={`card overflow-hidden sales-card ${gradientClass}`}>
                <div className="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <h6 className="mb-3 tx-12 text-white">{title}</h6>
                    <div className="pb-0 mt-0">
                        <div className="d-flex">
                            <div className="">
                                <h4 className="tx-20 font-weight-bold mb-1 text-white">{value}</h4>
                                <p className="mb-0 tx-12 text-white op-7">مقارنة بالأسبوع الماضي</p>
                            </div>
                            <span className="float-right my-auto mr-auto">
                                <i className={`fas fa-arrow-circle-${isIncrease ? 'up' : 'down'} text-white`}></i>
                                <span className="text-white op-7"> {percentage}</span>
                            </span>
                        </div>
                    </div>
                </div>
                {/* هنا يتم دمج مكتبة Sparklines لاحقاً للرسم البياني الصغير */}
                <span className="pt-1 text-white-50 tx-10">{chartData}</span>
            </div>
        </div>
    );
};
export default DashboardStatCard;