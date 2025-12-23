import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { createRouter, createWebHistory } from 'vue-router'
import Vue3Toastify, { type ToastContainerOptions } from 'vue3-toastify'

// Import de estilos
import 'vue3-toastify/dist/index.css'
import './style.css'

import App from './App.vue'
import Login from './views/Login.vue' // Vamos criar esta pasta/arquivo abaixo
import Dashboard from './views/Dashboard.vue'

const routes = [
  { path: '/login', component: Login },
  { path: '/', redirect: '/dashboard' },
  { path: '/dashboard', component: Dashboard }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(Vue3Toastify, {
  autoClose: 3000,
  position: 'top-right',
} as ToastContainerOptions)

app.mount('#app')