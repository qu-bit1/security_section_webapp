import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';
import {clickoutDirective} from "@/directives.js";
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'
import {DateTime} from "luxon";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})

        app.config.globalProperties.$filters = {
            formatDate: function (value, humanize = false) {
                const date = DateTime.fromISO(value);
                if (humanize){
                    return date.toRelative();
                }

                return date.toFormat('LLLL dd yyyy\nT');
            }
        }

        return app
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(LaravelPermissionToVueJS)
            .provide("can", app.config.globalProperties.can)
            .provide("is", app.config.globalProperties.is)
            .directive('clickout', clickoutDirective)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
