import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { createPinia } from 'pinia'
import AppLayout from './Layouts/AppLayout.vue'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true }) as any
    const page: any = pages[`./Pages/${name}.vue`]
    page.default.layout = page.default.layout || AppLayout
    return page
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.use(plugin).use(createPinia()).mount(el)
  },
})
