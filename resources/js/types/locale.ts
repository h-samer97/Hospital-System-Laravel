export type localeCode = 'ar' | 'en';
export type localDirection = 'ltr' | 'rtl';
import { Config } from 'ziggy-js';

export interface SupportedLocal {
    dir: localDirection;
    code: localeCode;
    url: string;
    native: string;
}

export interface SharedProps {
    locale:           localeCode;
    dir:              localDirection;
    supportedLocales: SupportedLocal[];
    auth: {
        user: { name: string; email: string } | null;
    };
    ziggy: Config & { location: string };
}