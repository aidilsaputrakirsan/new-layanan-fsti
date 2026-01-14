import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { 
    create,
    NConfigProvider,
    NMessageProvider,
    NDialogProvider,
    NNotificationProvider,
    NLoadingBarProvider
} from 'naive-ui'
import '../css/app.css'

const naive = create()

createInertiaApp({
    title: (title) => title ? `${title} - FSTI Dashboard` : 'FSTI Dashboard',
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({
            render: () => 
                h(NConfigProvider, null, {
                    default: () => h(NMessageProvider, null, {
                        default: () => h(NDialogProvider, null, {
                            default: () => h(NNotificationProvider, null, {
                                default: () => h(NLoadingBarProvider, null, {
                                    default: () => h(App, props)
                                })
                            })
                        })
                    })
                })
        })
            .use(plugin)
            .use(naive)
            .mount(el)
    },
    progress: {
        color: '#18a058',
    },
})
