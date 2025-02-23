import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Vapor from 'laravel-vapor';
import {VaporAssetsMixin} from "./Mixins/VaporAssetsMixin.js";

const appName = import.meta.env.VITE_APP_NAME || 'Contact CURD';

Vapor.withBaseAssetUrl(import.meta.env.VITE_VAPOR_ASSET_URL)

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mixin(VaporAssetsMixin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
