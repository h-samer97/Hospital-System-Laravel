export interface StatCardProps {
    title: string;
    value: string;
    percentage: string;
    isIncrease: boolean;
    gradientClass: string;
    chartData: string;
}

export interface Customer {
    id: string;
    name: string;
    img: string;
    status: 'Paid' | 'Pending';
}