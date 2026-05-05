import React from 'react';
import { Link } from '@inertiajs/react';

const Navigation: React.FC = () => {
    return (
        <nav className="navigation">
            <ul className="nav-links">
                <li><Link href="/dashboard">Dashboard</Link></li>
                <li><Link href="/patients">Patients</Link></li>
                <li><Link href="/doctors">Doctors</Link></li>
                <li><Link href="/sections">Sections</Link></li>
                <li><Link href="/ambulances">Ambulances</Link></li>
                <li><Link href="/insurance">Insurance</Link></li>
            </ul>
        </nav>
    );
};

export default Navigation;
