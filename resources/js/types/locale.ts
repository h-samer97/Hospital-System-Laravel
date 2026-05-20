export type localeCode = 'ar' | 'en';
export type localDirection = 'ltr' | 'rtl';
import { Config } from 'ziggy-js';

export interface SharedProps {
    locale: localeCode;
    dir: localDirection;
    auth: {
        user: { name: string; email: string } | null;
        admin: { name: string; email: string } | null;
    };
    ziggy: Config & { location: string };
}