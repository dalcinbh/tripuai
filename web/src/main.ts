import { createApp } from 'vue'
import { createPinia } from 'pinia'

import Vue3Toastify, { type ToastContainerOptions } from 'vue3-toastify'

// Import de estilos
import 'vue3-toastify/dist/index.css'
import './style.css'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(Vue3Toastify, {
  autoClose: 3000,
  position: 'top-right',
} as ToastContainerOptions)

app.mount('#app')