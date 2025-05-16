import 'vue-toastification/dist/index.css';
import '../css/app.css';

import { notifications } from '@/plugins/notifications';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { modal } from 'momentum-modal';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import Toast from 'vue-toastification';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;

        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(modal, {
                resolve: (name: string) => resolvePageComponent(`./Modals/${name}.vue`, import.meta.glob<DefineComponent>('./Modals/**/*.vue')),
            })
            .use(ZiggyVue)
            .use(Toast, {
                transition: 'Vue-Toastification__slideBlurred',
                maxToasts: 4,
                newestOnTop: true,
            })
            .use(notifications)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on a page load...
initializeTheme();
