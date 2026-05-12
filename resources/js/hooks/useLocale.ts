import { usePage } from '@inertiajs/react';
import { SharedProps, localeCode, SupportedLocal } from '@/types/locale';


export function useLocale() {
    const { locale, dir, supportedLocales } = usePage<SharedProps>().props;

    const isRTL = dir === 'rtl';

    // URL التبديل جاهز من mcamara — لا حاجة لـ POST request
    const getLocaleUrl = (code: localeCode): string => {
        const found = supportedLocales.find((l: SupportedLocal) => l.code === code);
        return found?.url ?? '#';
    };

    return { locale, dir, isRTL, supportedLocales, getLocaleUrl };
}