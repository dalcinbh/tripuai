import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', component: Login },
    { path: '/dashboard', component: () => import('../views/Dashboard.vue') },
    { path: '/', redirect: '/login' }
  ]
});

export default router;