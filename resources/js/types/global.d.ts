import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';
import { PageProps as AppPageProps } from './';
import { FlashMessage } from './models';

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    /* eslint-disable no-var */
    var route: typeof ziggyRoute;
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

// تعريف الـ props المشتركة في كل الصفحات
declare module '@inertiajs/react' {
  interface PageProps {
    auth: {
      user?: { id: number; name: string; email: string };
      admin?: { id: number; name: string; email: string };
    };
    flash?: FlashMessage;
    errors: Record<string, string>;
  }
}