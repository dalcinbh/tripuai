import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue';
import AboutView from '../views/AboutView.vue';
import PrivacyView from '../views/PrivacyView.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', component: Login },
    { path: '/dashboard', component: Dashboard },
    { path: '/about', component: AboutView },
    { path: '/privacy', component: PrivacyView },
    { path: '/', redirect: '/login' }
  ]
});

export default router;